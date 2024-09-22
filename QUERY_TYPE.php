<?php
namespace data ;

/** Estabelece os tipos de consulta que podem ser feitos num banco de dados. */
enum QUERY_TYPE: string {
 
 case ALL = "ALL" ;
 case RECENT = "RECENT" ;
 case LIKES = "LIKES" ;
 case VISITS = "VISITS" ;

 /**
  * FUNÇÃO AINDA NÃO IMPLEMENTADA.
  */
 public function get_key_to_order ( ) {

  switch ( $this->name ) {
   case "RECENT":{
    return "dateTime" ;
   }
   case "LIKES":{
    return "likes" ;
   }
   case "VISITS":{
    return "" ;
   }
  }

 }

}

?>