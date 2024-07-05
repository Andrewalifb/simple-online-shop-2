<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CartModel extends CI_Model {

    protected $table = 'carts';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('CartItemModel');
        $this->load->model('UserModel');
    }

    public function addCart($data)
    {
        // Perform input validation here
        if (empty($data['user_id'])) {
            throw new \Exception('User ID is required');
        }

        $userModel = new UserModel();
        $user = $userModel->find($data['user_id']);
        if (!$user) {
            throw new \Exception('User not found');
        }

        $this->db->insert($this->table, $data);
        return $this->db->where('id', $this->db->insert_id())->get($this->table)->row();
    }

    public function checkout($id)
    {
        // Perform input validation here
        $cart = $this->db->where('id', $id)->get($this->table)->row();
        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        if ($cart->status !== 'open') {
            throw new \Exception('Cart is not open');
        }

        $cartItems = $this->CartItemModel->getItemsInCart($id);
        if (empty($cartItems)) {
            throw new \Exception('Add items to the cart before checking out');
        }

        $this->db->where('id', $id)->update($this->table, ['status' => 'checked_out']);

        return $cartItems;
    }

    public function getShoppingHistory($userId)
    {
        $carts = $this->db->where('user_id', $userId)->get($this->table)->result();

        foreach ($carts as &$cart) {
            $cart->items = $this->CartItemModel->getItemsInCart($cart->id);
        }

        return $carts;
    }
}
