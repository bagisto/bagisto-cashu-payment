<?php

namespace Webkul\CashU\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Checkout\Repositories\CartRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Customer\Repositories\CustomerRepository;
use Illuminate\Support\Arr;
use SoapClient;
use Redirect;

/**
 * CashU controller
 *
 * @author    Rahul Shukla <rahulshukla,symfony517@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CashUController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var array
     */
    protected $orderRepository;

    /**
     * InvoiceRepository object
     *
     * @var object
     */
    protected $invoiceRepository;

    /**
     * CartRepository object
     *
     * @var object
     */
    protected $cartRepository;

    /**
     * ProductRepository object
     *
     * @var object
     */
    protected $product;

    /**
     * CustomerRepository instance
     *
     * @var mixed
     */
    protected $customer;

    /**
     * merchentId for cashU
     *
     */
    protected $merchentId = null;

    /**
     * encryptionKey for cashU
     *
     */
    protected $encryptionKey = null;

    /**
     * encryption for cashU
     *
     */
    protected $encryption;

    /**
     * transcationMode for cashU
     *
     */
    protected $transcationMode;

    /**
     * servicenName for cashU
     *
     */
    protected $serviceName = null;

    /**
     * displayText for cashU
     *
     */
    protected $displayText = null;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository $invoiceRepository
     * @param  \Webkul\Checkout\Repositories\CartRepository cartRepository
     * @param  Webkul\Product\Repositories\ProductRepository  $product
     * @param  Webkul\Customer\Repositories\CustomerRepository  $customer
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository,
        CartRepository $cartRepository,
        ProductRepository $product,
        CustomerRepository  $customer
    ) {
        $this->orderRepository   = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->cartRepository  = $cartRepository;
        $this->product         = $product;
        $this->customer        = $customer;
        $this->merchentId      = core()->getConfigData('sales.paymentmethods.cashu.merchent_id');
        $this->encryptionKey   = core()->getConfigData('sales.paymentmethods.cashu.encryption_keyword');
        $this->encryption      = core()->getConfigData('sales.paymentmethods.cashu.encryption');
        $this->transcationMode = core()->getConfigData('sales.paymentmethods.cashu.transcation_mode');
        $this->serviceName     = core()->getConfigData('sales.paymentmethods.cashu.service_name');
        $this->displayText     = core()->getConfigData('sales.paymentmethods.cashu.display_text');
    }

    /**
     * Redirects to the cashU.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect()
    {
        $transcationCode = $this->getTranscationCode();

        try {
            if ($transcationCode) {
                return view('cashu::redirect', compact('transcationCode'));
            } else {
                session()->flash('error', trans('cashu::app.configuration.error'));

                return redirect()->back();
            }
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }
    }

     /**
     * Cancel payment from cashu.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        $data = request()->all();
        $errorCode = [2,4,6,7,8,15,17,20,21,22,23,24,27,32];

        if (in_array($data['errorCode'], $errorCode)) {
            $errorString = 'cashu::app.error'.'.'.$data['errorCode'];

            session()->flash('error', trans($errorString));
        } else {
            session()->flash('error', trans('cashu::app.error.general'));
        }

        return redirect()->route('shop.checkout.onepage.index');
    }

    /**
     * Success payment
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        $data = request()->all();

        $calculatedVerificationString = sha1(strtolower($this->merchentId) . ':' . $data['trn_id'] . ':' . $this->encryptionKey);

        if ($calculatedVerificationString != $data['verificationString']) {
            return view('cashu::error');
        } else {
            if ($this->encryption == 'full') {
                $calculatedtoken = md5(strtolower($this->merchentId) . ':' . $data['txt2'] . ':' . strtolower($data['txt3']) . ':' . strtolower($data['session_id']) . ':' .
                $this->encryptionKey);
            } else {
                $calculatedtoken = md5(strtolower($this->merchentId) . ':' . $data['txt2'] . ':' . strtolower($data['txt3']) . ':' . $this->encryptionKey);
            }

            if ($calculatedtoken != $data['token']) {
                return view('cashu::error');
            } else {
                $cart = $this->cartRepository->findorFail($data['txt4']);
                $dataForOrder = $this->prepareDataForOrder($cart);
                $order = $this->orderRepository->create($dataForOrder);
                $this->orderRepository->update(['status' => 'processing'], $order->id);

                if ($order->canInvoice()) {
                    $this->invoiceRepository->create($this->prepareInvoiceData($order));
                }

                $this->cartRepository->update(['is_active' => false], $cart->id);

                return view('cashu::success', compact('order'));
            }
        }
    }

    /**
     * get payment transcation code
     *
     * @return \Illuminate\Http\Response
     */
    protected function getTranscationCode()
    {
        try {
            ini_set("soap.wsdl_cache_enabled", "0");
            ini_set('customer_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:20.0) Gecko/20100101 Firefox/20.0');

            $cart = Cart::getCart();
            $amount = $cart->base_grand_total + 0;
            $currency = strtolower($cart->base_currency_code);
            $language = strtolower(core()->getCurrentLocale()->code);
            $sessionId = Str::random(8);
            $this->serviceName =  $this->serviceName ? $this->serviceName : '';
            $txt1 = strtolower(core()->getCurrentChannel()->name);
            $txt2 = $cart->base_grand_total + 0;
            $txt3 = strtolower($cart->base_currency_code);
            $txt4 = $cart->id;
            $txt5 = '';

            if ($this->encryption == 'full') {
                $token = md5(strtolower($this->merchentId) . ':' . $amount . ':' . strtolower($currency) . ':' . strtolower($sessionId) . ':' . $this->encryptionKey);
            } else {
                $token = md5(strtolower($this->merchentId) . ':' . $amount . ':' . strtolower($currency) . ':' . $this->encryptionKey);
            }

            if ($this->transcationMode == 0) {
                $urlcash  = 'https://sandbox.cashu.com/secure/payment.wsdl';
                $testmode = 0;
            } else {
                $urlcash  = 'https://cashu.com/secure/payment.wsdl';
                $testmode = 1;
            }

            $client = new SoapClient($urlcash, array('trace' => true));

            $request = $client->DoPaymentRequest(
                $this->merchentId,
                $token,
                $this->displayText,
                $currency,
                $amount,
                $language,
                $sessionId,
                $txt1,
                $txt2,
                $txt3,
                $txt4,
                $txt5,
                $testmode,
                $this->serviceName
            );

            $tmp = strstr($request, '=');

            $Transaction_Code = substr($tmp, 1);

            return $Transaction_Code;
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));
        }
    }

    /**
     * Prepares order's invoice data for creation
     *
     *
     * @return array
     */
    protected function prepareInvoiceData($order)
    {
        $invoiceData = [
            "order_id" => $order->id
        ];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Validate order before creation
     *
     * @return array
     */
    public function prepareDataForOrder($cart)
    {
        $data = $this->toArray($cart);

        if (! is_null($data['customer_id'])) {
            $customer = $this->customer->findorFail($data['customer_id']);
        } else {
            $customer = null;
        }

        $finalData = [
            'cart_id' => $cart->id,

            'customer_id' => $data['customer_id'],
            'is_guest' => $data['is_guest'],
            'customer_email' => $data['customer_email'],
            'customer_first_name' => $data['customer_first_name'],
            'customer_last_name' => $data['customer_last_name'],
            'customer' => $customer,
            'shipping_method' => $data['selected_shipping_rate']['method'],
            'shipping_title' => $data['selected_shipping_rate']['carrier_title'] . ' - ' . $data['selected_shipping_rate']['method_title'],
            'shipping_description' => $data['selected_shipping_rate']['method_description'],
            'shipping_amount' => $data['selected_shipping_rate']['price'],
            'base_shipping_amount' => $data['selected_shipping_rate']['base_price'],

            'total_item_count' => $data['items_count'],
            'total_qty_ordered' => $data['items_qty'],
            'base_currency_code' => $data['base_currency_code'],
            'channel_currency_code' => $data['channel_currency_code'],
            'order_currency_code' => $data['cart_currency_code'],
            'grand_total' => $data['grand_total'],
            'base_grand_total' => $data['base_grand_total'],
            'sub_total' => $data['sub_total'],
            'base_sub_total' => $data['base_sub_total'],
            'tax_amount' => $data['tax_total'],
            'base_tax_amount' => $data['base_tax_total'],
            'discount_amount' => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],

            'shipping_address' => Arr::except($data['shipping_address'], ['id', 'cart_id']),
            'billing_address' => Arr::except($data['billing_address'], ['id', 'cart_id']),
            'payment' => Arr::except($data['payment'], ['id', 'cart_id']),

            'channel' => core()->getCurrentChannel(),
        ];

        foreach ($data['items'] as $item) {
            $finalData['items'][] = $this->prepareDataForOrderItem($item);
        }

        return $finalData;
    }

    /**
     * Returns cart details in array
     *
     * @return array
     */
    public function toArray($cart)
    {
        $cart = $this->cartRepository->findorFail($cart->id);

        $data = $cart->toArray($cart);

        $data['shipping_address'] = $cart->shipping_address->toArray();

        $data['billing_address'] = $cart->billing_address->toArray();

        $data['selected_shipping_rate'] = $cart->selected_shipping_rate->toArray();

        $data['payment'] = $cart->payment->toArray();

        $data['items'] = $cart->items->toArray();

        return $data;
    }

    /**
     * Prepares data for order item
     *
     * @return array
     */
    public function prepareDataForOrderItem($data)
    {
        $finalData = [
            'product' => $this->product->find($data['product_id']),
            'sku' => $data['sku'],
            'type' => $data['type'],
            'name' => $data['name'],
            'weight' => $data['weight'],
            'total_weight' => $data['total_weight'],
            'qty_ordered' => $data['quantity'],
            'price' => $data['price'],
            'base_price' => $data['base_price'],
            'total' => $data['total'],
            'base_total' => $data['base_total'],
            'tax_percent' => $data['tax_percent'],
            'tax_amount' => $data['tax_amount'],
            'base_tax_amount' => $data['base_tax_amount'],
            'discount_percent' => $data['discount_percent'],
            'discount_amount' => $data['discount_amount'],
            'base_discount_amount' => $data['base_discount_amount'],
            'additional' => $data['additional'],
        ];

        if (isset($data['child']) && $data['child']) {
            $finalData['child'] = $this->prepareDataForOrderItem($data['child']);
        }

        return $finalData;
    }
}
