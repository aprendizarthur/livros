<?php
declare(strict_types=1);
namespace traits;
/**
 * Trait responsável por validar inputs recebido no registro/update do objeto BookModel
 */
trait AuthBook
{
    /**
     * Método que valida o título do livro retornando um bool
     * @param string $title
     * @return boolean
     */
    public function AuthTitle(string $title) : bool {
        $bool = preg_match('/[^a-zA-Z0-9 \p{L}]/u', $title) ? false : true;
        return $bool;
    }

    /**
     * Método que valida o autor do livro retornando um bool
     * @param string $author
     * @return boolean
     */
    public function AuthAuthor(string $author) : bool {
        $bool = preg_match('/[^a-zA-Z0-9 \p{L}]/u', $author) ? false : true;
        return $bool;
    }

    /**
     * Método que valida o nº de páginas do livro retornando um bool
     * @param int $pages
     * @return boolean
     */
    public function AuthPages(int $pages) : bool {
        $bool = $pages <= 0 ? false : true;
        return $bool; 
    }

    /**
     * Método que valida o ano de publicação do livro retornando um bool
     * @param int $year
     * @return boolean
     */
    public function AuthYear(int $year) : bool {
        $thisYear = (int)date('Y');
        $bool = $thisYear < $year ? false : true;
        $bool2 = $year <= 0 ? false : true;
        if($bool == false || $bool2 == false){
            return false;
        }
        return true;
    }
}