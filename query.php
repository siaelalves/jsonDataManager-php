<?php
namespace data ;

/** Classe query. Gerencia e executa buscas nos bancos de dados de maneiras específicas. */
class query {

  /**
   * Obtém uma página com base na Url. Decompõe a Url em partes e depois compara a 
  * última parte da Url obtida  com as Urls de cada página publicada no banco de 
  * dados. Então, retorna o objeto de página que correspondente à Url.
  * @param \routes\url $url_to_get Url que se deseja obter o post.
  * @return array Retorna uma matriz que representa o objeto de página. Retorna false 
  * se não houver nenhuma página publicada com esta Url.
  */
  public function get_page_by_url( \routes\url $url_to_get ) {
   global $admin , $request;   
 
   foreach( $admin->public_pages as $page_obj ) {
    $page = new \blog\page ( $page_obj ) ;

    if ( $page->url->path->full == $url_to_get->path->full || $page->is_blog_page ( ) == true ) {
     return $page_obj ;
    }
 
   }

   foreach( $admin->admin_pages as $page_obj ) {
    $page = new \blog\page ( $page_obj ) ;
 
    if ( $page->url->path->full == $url_to_get->path->full ) {
     return $page_obj ;
    }
 
   }
 
   return false;
  }

 /**
  * Obtém uma categoria com base na Url. Decompõe a Url em partes e depois compara a 
  * última parte da Url obtida  com as Urls de cada categoria publicada no banco de 
  * dados. Então, retorna o objeto $category que correspondente à Url.
  * @param string $url_to_get Url que se deseja obter a categoria.
  * @return array Retorna uma matriz que representa o objeto $category. Retorna false 
  * se não houver nenhuma categoria publicada com esta Url.
  */
  public function get_category_by_url( $url_to_get ) {
   global $admin , $request;
   
   $url_to_get = $request->get_last_part_of_url( $url_to_get );
  
   foreach ( $admin->categories as $category ) {
    
    if ( $category["url"] == $url_to_get ) {
     return $category;
    }
 
   }
 
   return false;
  }

  public function get_tag_by_url( $url_to_get ) {
   global $admin , $request;
   
   $url_to_get = $request->get_last_part_of_url( $url_to_get );
  
   foreach ( $admin->tags as $tag ) {
    
    if ( $tag["url"] == $url_to_get ) {
     return $tag;
    }
 
   }
 
   return false;
  }

 /**
  * Obtém um post com base na Url. Decompõe a Url em partes e depois compara a 
  * última parte da Url obtida  com as Urls de cada post publicado no banco de 
  * dados. Então, retorna o objeto de post que correspondente à Url.
  * @param string $url_to_get Url que se deseja obter o post.
  * @return array Retorna uma matriz que representa o objeto de post. Retorna false 
  * se não houver nenhuma categoria publicada com esta Url.
  */
 public function get_post_by_url( $url_to_get ) {
  global $admin , $request;

  $url_to_get = $request->get_last_part_of_url( $url_to_get );

  foreach( $admin->posts as $post ) {

   if ( $post["url"] == $url_to_get ) {
    return $post;
   }

  }

  return false;
 }
 /**
  * Obtém um post com base na Url. Decompõe a Url em partes e depois compara a 
  * última parte da Url obtida  com as Urls de cada post publicado no banco de 
  * dados. Então, retorna o objeto de post que correspondente à Url.
  * @param string $url_to_get Url que se deseja obter o post.
  * @return array Retorna uma matriz que representa o objeto de post. Retorna false 
  * se não houver nenhuma categoria publicada com esta Url.
  */
  public function get_media_by_url( $url_to_get , $is_admin_page = false ) {
   global $admin , $request;
 
   $url_to_get = $request->get_last_part_of_url( $url_to_get );
 
   foreach( $admin->medias as $media ) {

    if ( $media["page"] == $url_to_get ) {
     return $media;
    }
 
   }
 
   return false;
   // return "POST_NOT_FOUND, #1, getPostByUrl()";
  }

  /** Verifica a existência de um post de acordo com a url especificada.
   * @param string $post_url URL do post a ser examinado.
   * @return bool Retorna True se o post existir e False se não existir.
  */
  public function post_exists ( $post_url ) {
   global $admin , $request ;
   
   if ( count ( explode ( "/" , $post_url ) ) > 0 ) {
    $post_url = $request->get_last_part_of_url( $post_url );
   }

   foreach ( $admin->posts as $post ) {
    if ( $post["url"] == $post_url ) {
     return true;
    }    
   }
   return false;
  }

  /**
   * Obtém a quantidade de posts do blog.
   * @return int Retorna a quantidade de posts no blog independente do status.
   */
  public function get_posts_count ( ) {
   global $admin ;
   return count ( $admin->posts ) ;
  }
  /**
   * Obtém os posts em ordem do mais recente ao mais antigo. Como funciona: Realiza uma consulta 
   * em cada um dos posts, adiciona uma nova chave no início da matriz de cada objeto 
   * de post chamada "compare" e dá a ela o valor numérico inteiro que permite 
   * comparação de datas. Esse número é obtido através da função strtotime(). Em 
   * seguida, ordena os posts por ordem descendente usando a função arsort() e 
   * seleciona a quantidade de posts especificadas em $count a partir do índice 
   * $start.
   * @param integer $start Índice inicial.
   * @param integer $count Quantidade de posts a retornar a partir do índice inicial, 
   * incluindo o próprio elemento $start.
   * @return array Retorna uma matriz contendo a quantidade $count de posts ordenados 
   * do mais recente ao mais antigo contando a partir do índice $start.
   */
  function get_recent_posts ( $start = 0 , $count = 0 , QUERY_ORDER $order = QUERY_ORDER::DESC, \blog\status $status = new \blog\status( 2 ) ) : array {
   global $admin ;

   $posts_selected = [ ] ;

   foreach ( $admin->posts as $post ) {

    if ( $post [ "status" ] != $status->value ) { continue ; }

    $post_copy [ "compare" ] = strtotime ( $post [ "dateTime" ] ) ;
    $postKeys = array_keys ( $post ) ;

    foreach ( $postKeys as $key ) {
     $post_copy [ $key ] = $post [ $key ] ;
    }

    array_push ( $posts_selected , $post_copy ) ;
   }

   if ( count ( $posts_selected ) > 0 ) {

    if ( $order === QUERY_ORDER::DESC ) {
     arsort ( $posts_selected ) ;

    } else if ( $order === QUERY_ORDER::ASC ) {
     asort ( $posts_selected ) ;

    }

   }

   $posts_selected = array_slice ( $posts_selected , $start , $count ) ;

   return $posts_selected ;
  }
  /**
   * Obtém os posts em ordem do mais curtido ao menos curtido. Como funciona?Realiza uma consulta 
   * em cada um dos posts, adiciona uma nova chave no início da matriz de cada objeto 
   * de post chamada "compare" e dá a ela o valor numérico inteiro que representa o 
   * número de likes do post. Esse número é obtido através da $post["likes"]. Em 
   * seguida, ordena os posts por ordem descendente usando a função arsort().
   * @param integer $maxPosts O número máximo de posts a retornar. Quando esse valor 
   * é atingido, a função para de adicioinar posts à matriz $post_copy.
   * @return array Retorna uma matriz contendo os dados de todos os posts ordenados 
   * do mais curtido ao menos curtido, e cada objeto de post recebe uma nova chave 
   * chamada "compare".
   */
  function get_posts_by_like ( $max_posts = 0 , QUERY_ORDER $order = QUERY_ORDER::DESC ) {
   global $admin ;
   $posts_by_like = [] ;
  
   foreach ( $admin->posts as $post ) {
    if ( $post [ "status" ] == 0 || $post [ "status" ] == 1 ) { continue ; }

    if ( $max_posts > 0 ) {
     if ( count ( $posts_by_like ) == $max_posts ) {
      break;
     }
    }
  
    $post_copy["compare"] = $post["likes"];
    $post_keys = array_keys ( $post ) ;
    foreach ( $post_keys as $key ) {
     $post_copy[$key] = $post[$key];
    }
  
    array_push ( $posts_by_like , $post_copy );
   }
  
   if ( count ( $posts_by_like ) > 0 ) {

    if ( $order === QUERY_ORDER::DESC ) {
     arsort ( $posts_by_like );
    } else if ($order === QUERY_ORDER::ASC ) {
     asort ( $posts_by_like ) ;
    }

   }
   return $posts_by_like ;
  }

  /**
   * Obtém uma lista de posts que pertencem a uma determinada categoria de id $category_id.
   * @param int $category_id Id da categoria a pesquisar.
   * @return array Retorna uma matriz de elementos do tipo \blog\post com os posts que 
   * pertencem à categoria especificada.
   */
  function get_posts_by_category ( int $category_id ) : array {
   global $admin ;

   $post_list = [ ] ;

   foreach ( $admin->posts as $post_obj ) {
    $post = new \blog\post ( ) ;
    $post->new ( $post_obj ) ;
    
    foreach ( $post->categories as $category ) {

     if ( $category->id == $category_id ) {

      array_push ( $post_list , $post ) ;

     }

    }

   }

   return $post_list ;   
  }
  /**
   * Obtém os posts em ordem do mais visitado ao menos visitado. Como funciona: Realiza uma consulta 
  * nas estatísticas e verifica quantas vezes o post já foi visitado. Salva o resultado 
  * da consulta na chave "compare" e depois reordena os posts usando a função arsort(). 
  * Por fim, separa a quantidade de posts especificadas em $count e retorna o grupo de 
  * posts numa matriz associativa.
  * @param integer $count O número máximo de posts a retornar. Quando esse valor 
  * é atingido, a função para de adicionar posts à matriz $post_copy.
  * @return array Retorna uma matriz contendo os dados de todos os posts ordenados 
  * do mais curtido ao menos curtido, e cada objeto de post recebe uma nova chave 
  * chamada "compare".
  */
  function get_posts_by_visit ( $count = 0 , QUERY_ORDER $order = QUERY_ORDER::DESC ) : array {
   global $admin ;
   $posts_by_visit = [] ;
  
   foreach ( $admin->posts as $post_obj ) {
    if ( $post_obj [ "status" ] == 0 || $post_obj [ "status" ] == 1 ) { continue ; }

    $post = new \blog\post ( ) ;
    $post->new ( $post_obj ) ;
  
    if ( $count > 0 ) {
     if ( count ( $posts_by_visit ) == $count ) {
      break;
     }
    }
  
    $post_copy["compare"] = $post->visits ;
    $post_keys = array_keys ( $post_obj ) ;
    foreach ( $post_keys as $key ) {
     $post_copy[$key] = $post_obj[$key];
    }
  
    array_push ( $posts_by_visit , $post_copy );
   }
  
   if ( count ( $posts_by_visit ) > 0 ) {
    
    if ( $order === QUERY_ORDER::DESC ) {
     arsort ( $posts_by_like );
    } else if ($order === QUERY_ORDER::ASC ) {
     asort ( $posts_by_like ) ;
    }

   }
   return $posts_by_visit ;
  }

  /**
   * FUNÇÃO NÃO IMPLEMENTADA.
   */
  public function new ( QUERY_DB $db , QUERY_TYPE $type , QUERY_ORDER $order , int $count ) {
   global $admin ;

   if ( $db === QUERY_DB::POSTS ) {

    if ( $type === QUERY_TYPE::RECENT ) {
     
     return $this->get_recent_posts ( 0 , $count, $order ) ;
 
    } else if ( $type === QUERY_TYPE::LIKES ) {

     return $this->get_posts_by_like ( $count, $order ) ;

    } else if ( $type === QUERY_TYPE::VISITS ) {

     return $this->get_posts_by_visit ( $count , $order ) ;
     
    }

   }

  }


}

?>