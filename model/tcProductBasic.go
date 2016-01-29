package model

import (
	"time"
)

type TcProductBasic struct {
	ProductBasicId            int       `json:"product_basic_id" xorm:"not null pk autoincr INT(11)"`
	ProductBasicCode          string    `json:"product_basic_code" xorm:"VARCHAR(50)"`
	ProductBasicName          string    `json:"product_basic_name" xorm:"VARCHAR(128)"`
	ProductBasicCategory      int       `json:"product_basic_category" xorm:"INT(11)"`
	ProductBasicSubCategory   int       `json:"product_basic_sub_category" xorm:"INT(11)"`
	ProductBasicCost          string    `json:"product_basic_cost" xorm:"DECIMAL(20,3)"`
	ProductBasicPrice         string    `json:"product_basic_price" xorm:"DECIMAL(20,3)"`
	ProductBasicSalesType     int       `json:"product_basic_sales_type" xorm:"INT(11)"`
	ProductBasicChargingType  int       `json:"product_basic_charging_type" xorm:"INT(11)"`
	ProductBasicCalcUnit      int       `json:"product_basic_calc_unit" xorm:"INT(11)"`
	ProductBasicServiceUnit   int       `json:"product_basic_service_unit" xorm:"INT(11)"`
	ProductBasicCanRenewal    int       `json:"product_basic_can_renewal" xorm:"INT(11)"`
	ProductBasicHasExpired    int       `json:"product_basic_has_expired" xorm:"INT(11)"`
	ProductBasicNeedOpen      int       `json:"product_basic_need_open" xorm:"INT(11)"`
	ProductBasicNeedHost      int       `json:"product_basic_need_host" xorm:"INT(11)"`
	ProductBasicUtime         time.Time `json:"product_basic_utime" xorm:"DATETIME"`
	ProductBasicDomainCost    string    `json:"product_basic_domain_cost" xorm:"DECIMAL(20,3)"`
	ProductBasicSmsCost       string    `json:"product_basic_sms_cost" xorm:"DECIMAL(20,3)"`
	ProductBasicIdcCost       string    `json:"product_basic_idc_cost" xorm:"DECIMAL(20,3)"`
	ProductBasicConfirmMethod string    `json:"product_basic_confirm_method" xorm:"VARCHAR(128)"`
	ProductBasicAtime         time.Time `json:"product_basic_atime" xorm:"DATETIME"`
	ProductBasicNum           int       `json:"product_basic_num" xorm:"INT(11)"`
}
