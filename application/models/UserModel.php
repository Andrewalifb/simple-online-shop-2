<?php defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {
    
    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }

    public function register($data)
    {
        // Perform input validation here
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Invalid email format');
        }

        if (!preg_match('/^[0-9]{10,15}$/', $data['phone'])) {
            throw new \Exception('Invalid phone number format');
        }

        $existingUser = $this->db->where('email', $data['email'])->or_where('phone', $data['phone'])->get($this->table)->row();
        if ($existingUser) {
            throw new \Exception('Email or phone number already exists');
        }

        $this->db->insert($this->table, $data);
        return $this->db->where('email', $data['email'])->get($this->table)->row();
    }
}
