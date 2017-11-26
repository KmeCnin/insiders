<?php

namespace App\Entity\Rule;

use App\Entity\NormalizableInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractRule implements RuleInterface, NormalizableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enabled;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $public;

    public function __construct()
    {
        $this->setEnabled(true);
        $this->setPublic(true);
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        $this->setSlug(self::slugify($name));

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function nameToSlug(): string
    {
        return self::slugify($this->name);
    }

    public function slugToName($slug): string
    {
        return self::unslugify($slug);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function normalize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'enabled' => $this->isEnabled(),
            'public' => $this->isPublic(),
        ];
    }

    public function getOwningStyle(): string
    {
        if (0 === strpos($this->getName(), 'Le ')) {
            return 'du '.substr($this->getName(), 2);
        }

        return 'de '.lcfirst($this->getName());
    }

    protected static function slugify(string $string): string
    {
        // Removes duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);

        // Returns the slug
        return strtolower(strtr($stripped, self::slugTable()));
    }

    protected static function unslugify(string $slug): string
    {
        return ucfirst(strtr($slug, self::slugTable()));
    }

    protected static function slugTable(): array
    {
        return [
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z',
            'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A',
            'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C',
            'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I',
            'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U',
            'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a',
            'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i',
            'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u',
            'û'=>'u', 'ý'=>'y', 'ỳ'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R',
            'ŕ'=>'r', '/' => '-', ' ' => '-', "'" => '-',
        ];
    }

    protected static function unslugTable(): array
    {
        return array_flip(self::slugTable());
    }
}
