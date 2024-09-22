<?php
/*
 © Copyright 2024, Siael Alves
*/
namespace data ;

/**
 * Lista de constantes com o nome do banco de dados deve ser alterado. Esta lista não retorna 
 * o caminho completo do banco de dados. Ela retorna apenas uma string que representa o caminho 
 * do banco de dados. Para obter uma url válida para o caminho do banco de dados, utilize a 
 * função get_db_path ( ) deste enumerador.
*/
enum QUERY_DB: string {

 case PAGES = "PAGES" ;
 case POSTS = "POSTS" ;
 case CATEGORIES =  "CATEGORIES" ;
 case TAGS = "TAGS" ;
 case MEDIAS = "MEDIAS" ;
 
 case DATE_TIME_FORMATS = "DATE_TIME_FORMATS" ;
 case IMAGE_SIZES = "IMAGE_SIZES" ;
 
 case EMAILS = "EMAILS" ;
 case STATISTICS = "STATISTICS" ;
 case ERRORS = "ERROS" ;
 case UPDATES = "UPDATES" ;

 /**
  * Obtém um caminho completo do banco de dados.
  * @return string Retorna uma string com o caminho completo do banco de dados.
  */
 public function get_db_path ( ) {
  global $paths ;

  switch ( $this->name ) {
   case "PAGES":{
    return $paths->public_page_db ;
   }

   case "POSTS":{
    return $paths->post_db ;
   }

   case "CATEGORIES":{
    return $paths->categories_db ;
   }

   case "TAGS": {
    return $paths->tags_db ;
   }

   case "MEDIAS": {
    return $paths->media_db ;
   }


   case "DATE_TIME_FORMATS": {
    return $paths->date_time_formats_db ;
   }
  }
 }

}
?>