    <?php

    class CarroController {

        private $model;
        private $marcaModel;

        public function __construct()
        {
            $this->model = new Carro();
            $this->marcaModel = new Marca();
        }

        public function index() {

            $dados = $this->model->listar();

            require __DIR__ . '/../views/carros/index.php';

        }

        public function adicionar() {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
                $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);

                $marca_id = filter_input(INPUT_POST, 'marca_id', FILTER_VALIDATE_INT);

                

                if ($modelo && $ano) {

                    $this->model->salvar(
                        $modelo,
                        
                        ($ano !== false && $ano !== null) ? $ano : null,
                        
                        ($marca_id !== false && $marca_id > 0) ? $marca_id : null
                    );

                    header('Location: /lp3_projeto/carros');
                    exit;

                }
            }

            $marcas = $this->marcaModel->listar();

            require __DIR__ . '/../views/carros/criar.php';

        }

        public function editar() {

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if (!$id) {

                header('Location: /lp3_projeto/carros');
                exit;

            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

               $modelo = filter_input(INPUT_POST, 'modelo', FILTER_SANITIZE_SPECIAL_CHARS);
                $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT);

                $marca_id = filter_input(INPUT_POST, 'marca_id', FILTER_VALIDATE_INT);

                // Busca os dados atuais do carro
                $dadoAtual = $this->model->buscarPorId($id);

                if (!$dadoAtual) {

                    header('Location: /lp3_projeto/carros');
                    exit;

                }

               

                if ($modelo && $ano) {

                    $this->model->atualizar(
                        $modelo,
                        
                        ($ano !== false && $ano !== null) ? $ano : null,
                        
                        ($marca_id !== false && $marca_id > 0) ? $marca_id : null
                    );

                    header('Location: /lp3_projeto/carros');
                    exit;

                }
            }

            // Busca os dados do carro para carregar a tela de edição
            $dado = $this->model->buscarPorId($id);

            if (!$dado) {

                header('Location: /lp3_projeto/carros');
                exit;

            }

            $marcas = $this->marcaModel->listar();

            require __DIR__ . '/../views/carros/editar.php';

        }

        public function excluir() {

            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($id) {

                $dado = $this->model->buscarPorId($id);

                if ($dado && !empty($dado['capa'])) {

                    $caminho = __DIR__ . '/../uploads/carros/' . $dado['capa'];

                    if (file_exists($caminho)) {
                        unlink($caminho);
                    }

                }

                $this->model->excluir($id);

            }

            header('Location: /lp3_projeto/carros');
            exit;

        }

      
    }
