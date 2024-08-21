<?php

namespace App\Command;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Symfony\Component\Panther\Client;
use Facebook\WebDriver\Exception\StaleElementReferenceException;
use Sentiment\Analyzer;

#[AsCommand(
    name: 'app:fetch-tweets',
    description: 'Escrapear los 100 tweets mas recientes con el hastag farina',
)]
class AppCommandFetchTweetsCommand extends Command
{
    public const URL_SELENIUM = 'http://selenium:4444';

    public function __construct(
        private TweetRepository $tweetRepository
    ) {
        parent::__construct();
    }


    protected function configure(): void
    {
        // Configuración adicional si es necesario
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $capabilities = DesiredCapabilities::firefox();
            $cliente = Client::createSeleniumClient(self::URL_SELENIUM, $capabilities);
            $crawler = $cliente->request('GET', 'https://x.com/search?q=%28%23farina%29&src=typed_query&f=live');
            $crawler = $cliente->waitFor('input[name="text"]');
            $crawler->filter('input[name="text"]')->sendKeys($_ENV['EMAIL_TWITTER']);
            sleep(2);
            $crawler->filter('button[class="css-175oi2r r-sdzlij r-1phboty r-rs99b7 r-lrvibr r-ywje51 r-184id4b r-13qz1uu r-2yi16 r-1qi8awa r-3pj75a r-1loqt21 r-o7ynqc r-6416eg r-1ny4l3l"]')->click();
            $crawler = $cliente->waitFor('input[name="text"]');
            $crawler->filter('input[name="text"]')->sendKeys($_ENV['USERNAME_TWITTER']);
            sleep(2);
            $crawler->filter('button[class="css-175oi2r r-sdzlij r-1phboty r-rs99b7 r-lrvibr r-19yznuf r-64el8z r-1fkl15p r-1loqt21 r-o7ynqc r-6416eg r-1ny4l3l"]')->click();
            $crawler = $cliente->waitFor('input[name="password"]');
            $crawler->filter('input[name="password"]')->sendKeys($_ENV['PASSWORD_TWITTER']);
            sleep(2);
            $crawler->filter('button[class="css-175oi2r r-sdzlij r-1phboty r-rs99b7 r-lrvibr r-19yznuf r-64el8z r-1fkl15p r-1loqt21 r-o7ynqc r-6416eg r-1ny4l3l"]')->click();
            sleep(10);
            $io->success('Página cargada.');

            $tweets = [];

            while (count($tweets) < 100) {
                try {
                    $crawler = $cliente->waitFor('article');
                    $newTweets = $crawler->filter('article')->each(function ($node) use ($io) {
                        if (strpos($node->text(), "Este post no está disponible.") !== false) {
                            $io->warning("Post no disponible, saltando...");
                            return null;
                        }

                        $publishedAtNode = $node->filter('time');
                        $authorNode = $node->filter('div[dir="ltr"][class="css-146c3p1 r-dnmrzs r-1udh08x r-3s2u2q r-bcqeeo r-1ttztb7 r-qvutc0 r-37j5jr r-a023e6 r-rjixqe r-16dba41 r-18u37iz r-1wvb978"] > span')->first();
                        $contentNode = $node->filter('div[lang]');
                        $commentsNode = $node->filter('button[data-testid="reply"]');
                        $retweetsNode = $node->filter('button[data-testid="retweet"]');
                        $likesNode = $node->filter('button[data-testid="like"]');
                        $viewsNode = $node->filter('div[class="css-175oi2r r-1kbdv8c r-18u37iz r-1wtj0ep r-1ye8kvj r-1s2bzr4"] > div:nth-child(4)');

                        if (!$publishedAtNode->count() || !$authorNode->count() || !$contentNode->count()) {
                            $io->warning('Nodo necesario no encontrado, saltando tweet...');
                            return null; // Retorna null si no se encuentran los nodos necesarios
                        }

                        $publishedAt = $publishedAtNode->attr('datetime');
                        $io->note("publishedAt => " . $publishedAt);
                        $author = $authorNode->text();
                        $io->note("Autor => " . $author);
                        $content = $contentNode->text();
                        $io->note("Content => " . $content);
                        $hashtags = $this->extractHashtags($content);
                        $io->note("Hashtags => " . implode(', ', $hashtags));
                        $comments = $commentsNode->count() ? ($commentsNode->text() !== '' ? $commentsNode->text() : '0') : '0';
                        $io->note("Comments => " . $comments);
                        $retweets = $retweetsNode->count() ? ($retweetsNode->text() !== '' ? $retweetsNode->text() : '0') : '0';
                        $io->note("Retweets => " . $retweets);
                        $likes = $likesNode->count() ? ($likesNode->text() !== '' ? $likesNode->text() : '0') : '0';
                        $io->note("Likes => " . $likes);
                        $views = $viewsNode->count() ? ($viewsNode->text() !== '' ? $viewsNode->text() : '0') : '0';
                        $io->note("Views => " . $views);
                        $images = $node->filter('img')->each(function ($img) {
                            return $img->attr('src');
                        });
                        $io->note("Images => " . implode(', ', $images));
                        $tweetId = md5($author . $publishedAt);
                        $io->note("tweetId => " . $tweetId);
                        $analyzer = new Analyzer();
                        $sentiment = $analyzer->getSentiment($content);
                        return [
                            'id' => $tweetId,
                            'author' => $author,
                            'published_at' => $publishedAt,
                            'content' => $content,
                            'hashtags' => $hashtags,
                            'comments' => $comments,
                            'retweets' => $retweets,
                            'likes' => $likes,
                            'views' => $views,
                            'images' => $images,
                            'sentiment' => $sentiment,
                        ];
                    });

                    foreach (array_filter($newTweets) as $tweet) {
                        $tweets[$tweet['id']] = $tweet;
                    }

                    $io->success(count($tweets) . " tweets recolectados.");
                    $cliente->executeScript('window.scrollTo(0, document.body.scrollHeight);');
                    sleep(5);

                } catch (StaleElementReferenceException $e) {
                    $io->warning('Elemento obsoleto encontrado, continuando con la ejecución...');
                    continue;
                }
            }

            $tweets = array_slice($tweets, 0, 100);

            foreach ($tweets as $value) {
                $tweet = $this->tweetRepository->findOneBy(['tweetId' => $value['id']]);
                if (!$tweet)
                    $tweet = new Tweet();
                $tweet->setTweetId($value['id']);
                $tweet->setAuthor($value['author']);
                $tweet->setPublishedAt(new \DateTimeImmutable($value['published_at']));
                $tweet->setContent($value['content']);
                $tweet->setHashtags($value['hashtags']);
                $tweet->setComments((int) $value['comments']);
                $tweet->setRetweets((int) $value['retweets']);
                $tweet->setLikes((int) $value['likes']);
                $tweet->setViews((int) $value['views']);
                $tweet->setImages($value['images']);
                $tweet->setSentiment($value['sentiment']);
                $this->tweetRepository->save($tweet, true);
            }

            $io->success('Se han recogido ' . count($tweets) . ' tweets.');
            $cliente->quit();
        } catch (NoSuchElementException $e) {
            $io->error('Elemento no encontrado: ' . $e->getMessage());
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error('Error inesperado: ' . $e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function extractHashtags(string $content): array
    {
        preg_match_all('/#(\w+|\p{L}+)/u', $content, $matches);
        return $matches[0];
    }

}
