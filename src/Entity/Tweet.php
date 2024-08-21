<?php

namespace App\Entity;

use App\Repository\TweetRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ApiResource(
    paginationItemsPerPage: 100
)]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $tweetId = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $published_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $hashtags = [];

    #[ORM\Column]
    private ?int $comments = null;

    #[ORM\Column]
    private ?int $retweets = null;

    #[ORM\Column]
    private ?int $likes = null;

    #[ORM\Column]
    private ?int $views = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $images = [];

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $sentiment = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTweetId(): ?string
    {
        return $this->tweetId;
    }

    public function setTweetId(string $tweetId): self
    {
        $this->tweetId = $tweetId;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeImmutable $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getHashtags(): array
    {
        return $this->hashtags;
    }

    public function setHashtags(?array $hashtags): self
    {
        $this->hashtags = $hashtags;

        return $this;
    }

    public function getComments(): ?int
    {
        return $this->comments;
    }

    public function setComments(int $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getRetweets(): ?int
    {
        return $this->retweets;
    }

    public function setRetweets(int $retweets): self
    {
        $this->retweets = $retweets;

        return $this;
    }

    public function getLikes(): ?int
    {
        return $this->likes;
    }

    public function setLikes(int $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getSentiment(): array
    {
        return $this->sentiment;
    }

    public function setSentiment(?array $sentiment): self
    {
        $this->sentiment = $sentiment;

        return $this;
    }
}
