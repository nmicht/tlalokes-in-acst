<?php
/**
 * AuthUsers Business Model
 * Copyright (C) 2010 Basilio Briceno Hernandez <bbh@tlalokes.org>
 *
 * This file is part of Tlalokes <http://tlalokes.org>.
 *
 * Tlalokes is free software: you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the
 * Free Software Foundation, version 3 of the License.
 *
 * Tlalokes Framework is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License
 * for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Tlalokes Framework.
 * If not, see <http://www.gnu.org/licenses/lgpl.html>.
 */
require_once 'Criteria.php';
require_once 'PropelPager.php';
require_once 'AuthUsersPeer.php';
/**
 * AuthUsers Business Model generated by Tlalokes's Auth-Propel-Smarty builder
 *
 * @author Basilio Brice&ntilde;o Hern&aacute;ndez <bbh@tlalokes.org>
 * @copyright Copyright &copy; 2010 Basilio Brice&ntilde;o Hern&aacute;ndez
 * @license http://www.gnu.org/licenses/lgpl.html GNU LGPL
 */
class AuthUsersBss {

  /**
   * Returns an existant user by email
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
        throw new Exception( "There is no such user" );
      }

      return array( 'id' => $obj->getId(),
                    'role' => $obj->getRole(),
                    'role_name' => $obj->getRole() ? $obj->getAuthRoles()->getName() : $obj->getRole(),
                    'email' => $obj->getEmail(),
                    'password' => $obj->getPassword(),
                    'user_status' => $obj->getUserStatus() );
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Returns every user
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

      $pager = new PropelPager( null, 'AuthUsersPeer', 'doSelect', $page, $limit );
      if ( !$pager->getResult() ) {
        throw new Exception( 'There is no data' );
      }

      $r['pager']['total_pages'] = $pager->getTotalPages();
      $r['pager']['total_records_count'] = $pager->getTotalRecordCount();
      $r['pager']['current'] = $pager->getPage();
      $r['pager']['next'] = $pager->getNext();
      $r['pager']['prev'] = $pager->getPrev();
      $r['pager']['limit'] = $limit;

      foreach ( $pager->getResult() as $obj ) {
        $r['data'][] = array( 'id' => $obj->getId(),
                              'role' => $obj->getRole(),
                              'role_name' => $obj->getRole() ? $obj->getAuthRoles()->getName() : $obj->getRole(),
                              'email' => $obj->getEmail(),
                              'password' => $obj->getPassword(),
                              'user_status' => $obj->getUserStatus() );
      }

      return $r;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Returns the content a user's content by it's primary key
   *
   * @return mixed
   */
  public static function getByPK ( $id )
  {
    try {
      $obj = AuthUsersPeer::retrieveByPK( $id );
      if ( !$obj ) {
        throw new Exception( "There is no data with key $id" );
      }

      return array( 'id' => $obj->getId(),
                    'role' => $obj->getRole(),
                    'role_name' => $obj->getRole() ? $obj->getAuthRoles()->getName() : $obj->getRole(),
                    'email' => $obj->getEmail(),
                    'password' => $obj->getPassword(),
                    'user_status' => $obj->getUserStatus() );
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }

  /**
   * Saves a new user
   *
   * @return mixed
   */
  public static function create ( TlalokesRequest &$request )
  {
    try {
      $obj = new AuthUsers;
      // validate id
      if ( isset( $request->id ) ) {
        $obj->setId( $request->id );
      }
      // validate email
      if ( !isset( $request->email ) || !$request->email ) {
        throw new Exception( 'Provide an email' );
      }
      // validate password
      if ( !isset( $request->password ) || !$request->password ) {
        throw new Exception( 'Provide a password' );
      }
      // validate status
      if ( !isset( $request->user_status ) || !$request->user_status ) {
        $request->user_status = 1;
      }
      // validate role
      if ( !isset( $request->role ) || !$request->role ) {
        throw new Exception( 'Provide a role' );
      }
      // set values and save
      $obj->setEmail( $request->email );
      $obj->setPassword( tlalokes_core_crypt( $request->password ) );
      $obj->setUserStatus( $request->user_status );
      $obj->setRole( $request->role );
      $obj->save();

      return self::getByPK( $obj->getId() );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize($e->getMessage()));
    }
  }

  /**
   * Updates an existant user
   *
   * @return mixed
   */
  public static function update ( TlalokesRequest &$request )
  {
    try {
      $obj = AuthUsersPeer::retrieveByPK( $request->_id );
      if ( $request->id ) {
        $obj->setId( $request->id );
      }
      if ( $request->role ) {
        $obj->setRole( $request->role );
      }
      if ( $request->email ) {
        $obj->setEmail( $request->email );
      }
      if ( $request->password ) {
        $obj->setPassword( tlalokes_core_crypt( $request->password ) );
      }
      if ( !$request->user_status ) {
        $request->user_status = 0;
      }
      $obj->setUserStatus( $request->user_status );
      $obj->save();

      return self::getByPK( $obj->getId() );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize( $e->getMessage() ));
    }
  }

  /**
   * Deletes an existant user
   *
   * @return mixed
   */
  public static function delete ( TlalokesRequest &$request )
  {
    try {
      AuthUsersPeer::doDelete( $request->_id );

      return self::getAll( $request );
    } catch ( PropelException $e ) {
      return preg_replace('/\	/','',tlalokes_str_sanitize( $e->getMessage() ));
    }
  }

  /**
   * Returns users by filter
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
        $c->add( AuthUsersPeer::ID, $request->id );
      }
      if ( $request->email ) {
        $c->add( AuthUsersPeer::EMAIL, $request->email );
      }
      if ( $request->user_status || $request->user_status === 0 ) {
        $c->add( AuthUsersPeer::USER_STATUS, $request->user_status );
      }
      if ( $request->role ) {
        $c->add( AuthUsersPeer::ROLE, $request->role );
      }

      if ( $page > 1 && $limit == 0 ) {
        throw new Exception( "There is no page $page" );
      }

      $pager = new PropelPager( $c, 'AuthUsersPeer', 'doSelect', $page, $limit );
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
                              'role' => $obj->getRole(),
                              'role_name' => $obj->getRole() ? $obj->getAuthRoles()->getName() : $obj->getRole(),
                              'email' => $obj->getEmail(),
                              'password' => $obj->getPassword(),
                              'user_status' => $obj->getUserStatus() );
      }

      return $r;
    } catch ( Exception $e ) {
      return $e->getMessage();
    }
  }
}
?>