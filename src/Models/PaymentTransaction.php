<?php
namespace Cyphergarden\Cypherpay\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    public $table = 'payment_transactions';

    protected $primaryKey = 'transaction_id';

    public $fillable=['reference_id','user_id','description','amount','successIndicator','status','session_id','session_version'];



}
