<?
require_once 'Criteria.php';
require_once 'PropelPager.php';
require_once 'AuthRolesPeer.php';

/**
 * AuthRoles Business Model generated by Tlalokes Framework
 *
 * @author Tlalokes Framework
 */
class AuthRolesBss {

  /**
   * Returns every elements
   *
   * @return mixed
   */
  public static function getByName ( $name )
  {
    try {
      $c = new Criteria;
      $c->add( AuthRolesPeer::NAME, $name );

      $obj = AuthRolesPeer::doSelectOne( $c );

      if ( !$obj ) {
        throw new Exception( 'There are no roles named ' . $name );
      }

      return array( 'id' => $obj->getId(),
                    'name' => $obj->getName(),
                    'role_status' => $obj->getRoleStatus() );
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Returns a user by it's email
   *
   * @return mixed
   */
  public static function getByEmail ( $email )
  {
    try {
      $c = new Criteria;
      $c->add( AuthUsersPeer::EMAIL, $email );

      $obj = AuthUsersPeer::doSelectOne( $c );
      if ( !$obj ) {
        throw new Exception( 'There is no user email ' .$email );
      }

      return array( 'id' => $obj->getId(),
                    'role' => $obj->getRoleid(),
                    'email' => $obj->getEmail() );
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }


  /**
   * Returns every elements
   *
   * @return mixed
   */
  public static function getAll ( TlalokesRequest &$request )
  {
    try {
      $page = isset( $request->page ) ? $request->page : 1;
      $limit = isset( $request->limit ) ? $request->limit : 0;
      if ( $page > 1 && $limit == 0 ) {
        throw new Exception( "There is no page $page" );
      }

      $pager = new PropelPager( null, 'AuthRolesPeer', 'doSelect', $page, $limit );
      if ( !$pager->getResult() ) {
        throw new Exception( 'There are no roles' );
      }

      $r['pager']['total_pages'] = $pager->getTotalPages();
      $r['pager']['total_records_count'] = $pager->getTotalRecordCount();
      $r['pager']['current'] = $pager->getPage();
      $r['pager']['next'] = $pager->getNext();
      $r['pager']['prev'] = $pager->getPrev();
      $r['pager']['limit'] = $limit;

      foreach ( $pager->getResult() as $obj ) {
        $r['data'][] = array( 'id' => $obj->getId(),
                              'name' => $obj->getName(),
                              'role_status' => $obj->getRoleStatus() );
      }

      return $r;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Returns the content of an element by it's primary key
   *
   * @return mixed
   */
  public static function getByPK ( $id )
  {
    try {
      $obj = AuthRolesPeer::retrieveByPK( $id );
      if ( !$obj ) {
        throw new Exception( 'There is no role with id ' . $id );
      }

      return array( 'id' => $obj->getId(),
                    'name' => $obj->getName(),
                    'role_status' => $obj->getRoleStatus() );
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Saves a new element
   *
   * @return mixed
   */
  public static function create ( TlalokesRequest &$request )
  {
    try {
      $obj = new AuthRoles;
      if ( isset( $request->id ) && $request->id ) {
        $obj->setId( $request->id );
      }
      if ( !isset( $request->name ) || !$request ) {
        throw new Exception( 'Provide a name' );
      }
      if ( !isset( $request->role_status ) || !$request->role_status ) {
        $request->role_status = 0;
      }
      $obj->setName( $request->name );
      $obj->setRoleStatus( $request->role_status );
      $obj->save();

      return self::getByPK( $obj->getId() );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize($e->getMessage()));
    }
  }

  /**
   * Updates an element
   *
   * @return mixed
   */
  public static function update ( TlalokesRequest &$request )
  {
    try {
      $obj = AuthRolesPeer::retrieveByPK( $request->_id );
      if ( isset( $request->id ) && $request->id ) {
        $obj->setId( $request->id );
      }
      if ( isset( $request->name ) && $request->name ) {
        $obj->setName( $request->name );
      }
      if ( !isset( $request->role_status ) || !$request->role_status ) {
        $request->role_status = 0;
      }
      $obj->setRoleStatus( $request->role_status );
      $obj->save();

      return self::getByPK( $obj->getId() );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize( $e->getMessage() ));
    }
  }

  /**
   * Deletes an element
   *
   * @return mixed
   */
  public static function delete ( TlalokesRequest &$request )
  {
    try {
      AuthRolesPeer::doDelete( $request->_id );

      return self::getAll( $request );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize( $e->getMessage() ));
    }
  }

  /**
   * Returns an element filtering its contents
   *
   * @return mixed
   */
  public static function filter ( TlalokesRequest &$request )
  {
    try {
      $page = isset( $request->page ) ? $request->page : 1;
      $limit = isset( $request->limit ) ? $request->limit : 0;

      // set Criteria object
      $c = new Criteria();
      $c->setIgnoreCase( true );

      // add elements to criteria
      if ( $request->id ) {
        $c->add( AuthRolesPeer::ID, $request->id );
      }
      if ( $request->name ) {
        $c->add( AuthRolesPeer::NAME, "%{$request->name}%", Criteria::LIKE );
      }
      if ( $request->role_status || $request->role_status === 0 ) {
        $c->add( AuthRolesPeer::ROLE_STATUS, $request->role_status );
      }

      if ( $page > 1 && $limit == 0 ) {
        throw new Exception( "There is no page $page" );
      }

      $pager = new PropelPager( $c, 'AuthRolesPeer', 'doSelect', $page, $limit );
      if ( !$pager->getResult() ) {
        throw new Exception( 'There are no coincidences' );
      }

      $r['pager']['total_pages'] = $pager->getTotalPages();
      $r['pager']['total_records_count'] = $pager->getTotalRecordCount();
      $r['pager']['current'] = $pager->getPage();
      $r['pager']['next'] = $pager->getNext();
      $r['pager']['prev'] = $pager->getPrev();
      $r['pager']['limit'] = $limit;

      foreach ( $pager->getResult() as $obj ) {
        $r['data'][] = array( 'id' => $obj->getId(),
                              'name' => $obj->getName(),
                              'role_status' => $obj->getRoleStatus() );
      }

      return $r;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }
}