<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ItemModel extends CI_Model {

    protected $table = 'items';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function find($id)
    {
        return $this->db->get_where($this->table, array('id' => $id))->row();
    }
    
    public function addItem($data)
    {
        // Perform input validation here
        if (empty($data['name']) || empty($data['price'])) {
            throw new \Exception('Name and price are required');
        }

        if (!is_numeric($data['price']) || $data['price'] < 0) {
            throw new \Exception('Invalid price');
        }

        $this->db->insert($this->table, $data);
        return $this->db->where('name', $data['name'])->get($this->table)->row();
    }

    public function editItem($id, $data)
    {
        // Perform input validation here
        if (empty($data['name']) || empty($data['price'])) {
            throw new \Exception('Name and price are required');
        }

        if (!is_numeric($data['price']) || $data['price'] < 0) {
            throw new \Exception('Invalid price');
        }

        $this->db->where('id', $id)->update($this->table, $data);
        return $this->db->where('id', $id)->get($this->table)->row();
    }
}
