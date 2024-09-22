<?php
/*
 © Copyright 2024, Siael Alves
*/
namespace data ;

/**
 * Classe database, que organiza e gerencia operações que afetam diretamente a estrutura do banco de dados. Esta classe só funcionará 
 * corretamente válida para bancos de dados que usam arquivos JSON.
 */
class database {

 /** Nome do banco de dados a ser alterado. */
 public $name ;

 /** Objeto que represnta o caminho completo do arquivo de banco de dados. */
 public string $file ;

 /** Objeto que representa os dados do arquivo do banco de dados em formato de matriz associativa. */
 public array $data ;

 public int $id_count ;


 /**
  * Constrói o objeto database. 
  * @param string $file Caminho completo do arquivo do banco de dados.
  */
 public function __construct( string $file ) {

  $this->file = $file ;
  $this->data = json_decode ( file_get_contents ( $this->file ) , true ) ;
  $this->id_count = count ( $this->data ) ;
  
 }
 /**
  * Adiciona uma propriedade a um arquivo de banco de dados Json numa eventual alteração e atualização do banco de dados.
 *  Se o nome de alguma das chaves de matriz associativa que já existir na lista de propriedades do arquivo, a propriedade
 *  e seus dados serão substituídos.
 * @param array $properties Matriz associativa com todos as chaves e valores padrão que se deseja adicionar ao banco de 
 * dados. Cada chave dessa matriz será adicionada ao banco de dados e será inserido o valor correspondente de cada chave.
 * @return void Não retorna valor.
 */
 public function add_property ( $properties = ["new_key" => "value"] ) : void {

  $newData = [] ;

  foreach ( $this->data as $entry ) {

   $new_keys = array_keys ( $properties ) ;

   foreach ( $new_keys as $key ) {

    $entry [ $key ] = $properties [ $key ] ;

   }

   $newData[] = $entry;

  }
  //
  file_put_contents ( $this->file , json_encode ( $newData ) , 0);
  return;

 }

 /**
  * Privado. Sobreescreve os valores $data dentro de $file. É usado quando as informações em $data são 
  * alteradas internamente pela classe.
  */
 private function save ( ) : bool {

  try {
   
   file_put_contents ( $this->file , json_encode ( $this->data ) ) ;
   return true ;

  } catch (\Throwable $th) {
   
   echo "Ocorreu um erro ao salvar os dados no arquivo " . $this->file . ". Erro na linha " . $th->getLine ( ) . ". Mensagem do erro: " . $th->getMessage ( ) ;
   return false ;

  }  

 }

 /**
  * Edita os dados de um banco de dados. Verifica se as chaves especificadas em $obj existem dentro 
  * da entrada $id do banco de dados. Se a chave não existir, nada é alterado e nenhuma mensagem de 
  * é apresentada. Depois que o objeto $this->data é alterado com cada uma das chaves especificadas, 
  * é executado o comando save( ) para sobrescrever os novos dados no arquivo.
  * @param int $id Id do banco de dados a ser alterado.
  * @param array $obj Pares de chaves e valores a serem alterados na entrada que corresponde a $id.
  * @return bool Retorna True se não ocorrer erros. False se algum erro ocorrer. Esta função 
  * invoca, em sua conclusão, uma função privada chamada save( ) que também retorna um valor 
  * booleano.
  */
 public function edit ( int $id , array $obj ) : bool {

  foreach ( array_keys ( $obj ) as $key ) {

   if ( key_exists ( $key , $this->data [ $id ] ) == true ) {

    $this->data [ $id ] [ $key ] = $obj [ $key ] ;
 
   }

  }

  return $this->save ( ) ;
  
 }

 /**
  * Adiciona uma nova entrada de dados ao banco de dados $file. Não há necessidade de especificar 
  * o valor da chave ["id"] em $obj. Esse valor é automaticamente definido de acordo com a quantidade 
  * de entradas já existentes no banco de dados.
  * @param array $obj Matriz associativa de dados a adicionar.
  * @return bool Retorna True a operação for bem-sucedida. False, se ocorrer algum erro.
  */
 public function add ( array $obj ) : bool {

  $obj [ "id" ] = $this->id_count ;
  array_push ( $this->data , $obj ) ;
  return $this->save ( ) ;

 }

 /**
  * Deleta os dados de um id de um banco de dados $file. A entrada no banco de dados não é efetivamente 
  * eliminada, mas as informações, com exceção do campo "id", são substituídas por null.
  * @param int $id Id do banco de dados a ser alterado.
  * @return bool Retorna True se o id especificado for encontrado. False se não for encontrado.
  */
 public function delete ( int $id ) : bool {

  foreach ( $this->data as $data ) {

   if ( $id == $data [ "id" ] ) {

    foreach ( array_keys ( $data ) as $key ) {

     if ( $key != "id" ) {
      
      $this->data [ $id ] [ $key ] = null ;

     }

    }
   
    return $this->save ( ) ;

   }
   
  }

  return false ;

 }

}

?>