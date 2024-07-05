<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CartItemModel extends CI_Model {

    protected $table = 'cart_items';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('ItemModel');
    }

    public function addItemToCart($data)
    {
        // Perform input validation here
        if (empty($data['cart_id']) || empty($data['item_id']) || empty($data['quantity'])) {
            throw new \Exception('Cart ID, Item ID, and Quantity are required');
        }

        if (!is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            throw new \Exception('Invalid quantity');
        }

        $this->db->insert($this->table, $data);
        $insertedData = $this->db->where('cart_id', $data['cart_id'])->get($this->table)->row();

        $itemModel = new ItemModel();
        $item = $itemModel->find($data['item_id']);
        $insertedData->name = $item->name;
        $insertedData->price = $item->price;
        $insertedData->subtotal = $item->price * $data['quantity'];

        return $insertedData;
    }

    public function getItemsInCart($cartId)
    {
        return $this->db->select('cart_items.id, cart_items.quantity, items.name, items.price')
                        ->join('items', 'items.id = cart_items.item_id')
                        ->where('cart_id', $cartId)
                        ->get($this->table)
                        ->result();
    }
}
