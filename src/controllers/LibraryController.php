<?php
declare(strict_types=1);
namespace controllers;
use database\BookDAO;
use models\BookModel;

class LibraryController
{
    private $bookModel;
    private $bookDAO;

    /**
     * Método que registra um livro no DB associado a um usuário, pegando dados do POST pelo
     * BookModel e realizando queries pelo BookDAO
     * @param BookModel $bookModel
     * @param BookDAO $bookDAO
     * @param array $dadosPOST
     * @return void
     */
    public function RegisterBook(BookModel $bookModel, BookDAO $bookDAO, array $dadosPOST) : void {
        $this->bookModel = $bookModel;
        $this->bookDAO = $bookDAO;
        
        if(!empty($dadosPOST)){
            //validando inputs na classe bookModel
            if(!$this->bookModel->AuthTitle($dadosPOST['title'])){
                throw new \Exception("Título inválido");
            }

            if(!$this->bookModel->AuthAuthor($dadosPOST['author'])){
                throw new \Exception("Autor inválido");
            }

            if(!$this->bookModel->AuthPages($dadosPOST['pages'])){
                throw new \Exception("Nº de páginas inválido");
            }

            if(!$this->bookModel->AuthYear($dadosPOST['year'])){
                throw new \Exception("Um livro não pode ser registrado como publicado em um ano futuro ou negativo");
            }
            
            //cadastrando usuário no DB com BookDAO recebendo os dados do POST validados
            $this->bookDAO->RegisterBookDB((int)$_SESSION['user-id'], $dadosPOST['title'], $dadosPOST['author'], $dadosPOST['genre'], (int)$dadosPOST['pages'] ?? 0, (int)$dadosPOST['year'] ?? 0);
            //encaminhar user para library
            header("Location: library.php");
            exit();
        }
    }

    /**
     * Método que realiza pesquisa no DB e printa na view o resultado
     * @param BookDAO $bookDAO
     * @return void
     */
    public function SearchBook(BookDAO $bookDAO) : void {
        $this->bookDAO = $bookDAO;
        $userID = (int)$_SESSION['user-id'];
        //sanizitando a pesquisa do get e exibindo resultado para o usuário
        if(isset($_GET['search'])){
            $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);

            //criar padrões para pesquisa filtrando por status
            //algo como if search = lidos (enviado por get ao clicar no filtro)

            //pegando dados de resultado da pesquisa no DB
            $data = $this->bookDAO->Search($userID, $search);
            $totalResultados = (int)count($data);

            //exibindo resultados
            if(empty($data)){
                //se não tiver livros no DB exibe atalho para adicionar livro
                echo '
                    <div class="col-12 col-md-6 col-lg-4 box-add-book text-center" style="color: #C7C7C7;">
                        <a href="library.php" style="color: #C7C7C7;">
                            <i class="fa-solid fa-heart-crack fa-xl mb-4"></i>
                            <p class="dm-sans-bold">Nenhum resultado encontrado</p>   
                            <small class="dm-sans-bold">Clique aqui e volte para biblioteca</small>                    
                        </a>
                    </div>
                ';
            } else {

                //printando atalho que volta para a biblioteca
                echo '
                <div class="col-12 col-md-6 col-lg-4 box-add-book text-center" style="color: #C7C7C7;">
                    <a href="library.php" style="color: #C7C7C7;">
                    <i class="fa-solid fa-magnifying-glass fa-xl mb-4"></i>
                        <p class="dm-sans-bold">Sua pesquisa teve '.$totalResultados.' resultado(s)</p> 
                        <small class="dm-sans-bold">Clique aqui e volte para biblioteca</small>              
                    </a>
                </div>
                ';

                foreach($data as $book){
                    //determinando se existe notes e data de leitura
                    $existsNotes = empty($book['notes']) ? "Não" : "Sim";
                    $dataLeitura = $book['readingdate'] === "0000-00-00" ? "Não" : $book['readingdate'];
                    $iconeStatus = match($book['bookstatus']){
                                    'em-leitura' => 'fa-clock',
                                    'por-ler' => 'fa-circle-exclamation',
                                    'lidos' => 'fa-circle-check',
                                    };

                    echo '
                        <div class="col-12 col-md-6 col-lg-4 box-book '.$book['bookstatus'].'">
                            <article>
                                <a href="book.php?id='.$book['id'].'">
                                    <header>
                                        <i class="fa-solid '.$iconeStatus.' fa-xl mr-1 '.$book['bookstatus'].'-livros"></i>
                                        <span class="dm-sans-light '.$book['bookstatus'].' px-2">'.$book['genre'].'</span>
                                        <h2 class="dm-sans-bold mt-3 mb-0">'.$book['title'].'</h2>
                                        <small class="dm-sans-light"><i>'.$book['author'].', '.$book['publicationyear'].'</i></small>
                                        <hr class="my-2">
                                    </header>
                                    <footer>
                                        <span><i class="fa-solid fa-file mr-1 fa-sm '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['pages'].'</small></span>
                                        <span><i class="fa-solid fa-database fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['registerdate'].'</small></span>
                                        <span><i class="fa-solid fa-clipboard mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$existsNotes.'</small></span>
                                        <span><i class="fa-solid fa-book-open-reader fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$dataLeitura.'</small></span>
                                    </footer>
                                </a>
                            </article>
                        </div>
                    ';
                }
            }
        }
    }

    /**
     * Método que exibe todos os livros da biblioteca do usuário
     * @param BookDAO $bookDAO
     * @return void
     */
    public function ShowLibrary(BookDAO $bookDAO) : void {
        //enquanto não houver pesquisa executa o método
        if(!isset($_GET['search'])){
            $this->bookDAO = $bookDAO;

            //pegando dados da biblioteca do usuário
            $data = $this->bookDAO->UserLibrary((int)$_SESSION['user-id']);

            //exibindo resultados
            if(empty($data)){
                //se não tiver livros no DB exibe atalho para adicionar livro
                echo '
                    <div class="col-12 col-md-6 col-lg-4 box-add-book">
                        <a href="newbook.php">
                            <i class="fa-solid fa-circle-plus fa-2xl"></i>
                        </a>            
                    </div>
                ';
            } else {
                foreach($data as $book){
                    //determinando se existe notes, data de leitura e qual o ícone de status do livro
                    $existsNotes = empty($book['notes']) ? "Não" : "Sim";
                    $dataLeitura = $book['readingdate'] === "0000-00-00" ? "Não" : $book['readingdate'];
                    $iconeStatus = match($book['bookstatus']){
                                    'em-leitura' => 'fa-clock',
                                    'por-ler' => 'fa-circle-exclamation',
                                    'lidos' => 'fa-circle-check',
                                    };
                    
                    echo '
                        <div class="col-12 col-md-6 col-lg-4 box-book '.$book['bookstatus'].'">
                            <article>
                                <a href="book.php?id='.$book['id'].'">
                                    <header>
                                        <i class="fa-solid '.$iconeStatus.' fa-xl mr-1 '.$book['bookstatus'].'-livros"></i>
                                        <span class="dm-sans-light '.$book['bookstatus'].' px-2">'.$book['genre'].'</span>
                                        <h2 class="dm-sans-bold mt-3 mb-0">'.$book['title'].'</h2>
                                        <small class="dm-sans-light"><i>'.$book['author'].', '.$book['publicationyear'].'</i></small>
                                        <hr class="my-2">
                                    </header>
                                    <footer>
                                        <span><i class="fa-solid fa-file mr-1 fa-sm '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['pages'].'</small></span>
                                        <span><i class="fa-solid fa-database fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$book['registerdate'].'</small></span>
                                        <span><i class="fa-solid fa-clipboard mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$existsNotes.'</small></span>
                                        <span><i class="fa-solid fa-book-open-reader fa-sm mr-1 '.$book['bookstatus'].'-livros-light"></i><small class="dm-sans-light mr-1">'.$dataLeitura.'</small></span>
                                    </footer>
                                </a>
                            </article>
                        </div>
                    ';
                }
            }
        }
    }
}