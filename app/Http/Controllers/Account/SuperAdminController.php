<?php

namespace App\Http\Controllers\Account;
use App\Http\Controllers\Controller as Controllers;
/**
 *
 */
class SuperAdminController extends Controllers
{
  protected $table;

  function __construct()
  {
    parent::__construct();
    $this->table = 'account';
  }

  public function index() {
    return $this->get($this->table);
  }

  // Insert new account
  public function store() {
    return request()->all();
  }
}
