<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\InternalException;
use Carbon\Carbon;

class Coupon extends Model
{
   protected $fillable=[
	'title',
	'receive_type',
	'type',
	'type_value',
	'min_condition_money',
	'amount',
	'receive_amount',
	'used_amount',
	'start_time',
	'end_time',
	'start_use_time',
	'end_use_time',
	'status',
	'use_scope',
   ];

   protected $dates=[
     'start_time',
	  'end_time',
	  'start_use_time',
	  'end_use_time',
   ];

   protected $casts=[
   	'status'=>'boolean'
   ];

   // 发放类型
   const ORDER_GIFT='order_gift'; 
   const DESIGNATION='designation';
   const FREE_COLLECTION='free_collection';
   const OFFLINE_PAYMENT='offline_payment';

   // 折扣类型
   const DISCOUNT='discount';
   const REDUCE='reduce';

   // 使用范围
   const WHOLE_STORE='whole_store';
   const DESIGNATION_GOODS='designation_goods';
   const DESIGNATION_GOODS_CATEGORY='designation_category';

   static $receiveTypeMaps=[
   		self::ORDER_GIFT=>'下单赠送',
   		self::DESIGNATION=>'指定发放',
   		self::FREE_COLLECTION=>'免费领取',
   		self::OFFLINE_PAYMENT=>'线下发放'
   ];

   static $typeMaps=[
   		self::DISCOUNT=>'优惠折扣',
   		self::REDUCE  =>'减免金额'
   ];

   static $useScopeMaps=[
   		self::WHOLE_STORE=>'全店通用',
   		self::DESIGNATION_GOODS=>'指定商品可用',
   		self::DESIGNATION_GOODS_CATEGORY=>'指定分类商品可用'
   ];

   public function calculatedAmount($total)
   {
         $this->checkCouponAvilable();

   		if ($total<$this->min_condition_money) {
   			throw new InternalException('不符合使用条件');
   		}

   		if ($this->type==self::REDUCE) {
   			return ($total-$this->type_value>0)?number_format($total-$this->type_value,2,'.', ''):0.01;
   		}elseif($this->type==self::DISCOUNT){
   			return number_format($total*(100-$this->type_value),2,'.', '');
   		}

   }

   public function checkCouponAvilable()
   {
      if (!$this->status
         ||$this->start_use_time->gt(Carbon::now())
         ||$this->end_use_time->lt(Carbon::now())
      ){
         throw new InternalException('不符合使用条件或优惠券已过期');
      }

      return true;
   }



}
