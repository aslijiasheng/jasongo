package model

import (
	"time"
)

type TcOrder struct {
	OrderId                  int       `json:"order_id" xorm:"not null pk autoincr INT(11)"`
	OrderNumber              string    `json:"order_number" xorm:"index VARCHAR(128)"`
	OrderCreateUserId        int       `json:"order_create_user_id" xorm:"INT(11)"`
	OrderCreateTime          time.Time `json:"order_create_time" xorm:"DATETIME"`
	OrderDepartment          int       `json:"order_department" xorm:"INT(11)"`
	OrderOwner               int       `json:"order_owner" xorm:"INT(11)"`
	OrderAccount             int       `json:"order_account" xorm:"INT(11)"`
	OrderAgent               int       `json:"order_agent" xorm:"INT(11)"`
	OrderAgreementNo         string    `json:"order_agreement_no" xorm:"VARCHAR(225)"`
	OrderAmount              string    `json:"order_amount" xorm:"DECIMAL(20,2)"`
	OrderFinance             int       `json:"order_finance" xorm:"INT(11)"`
	OrderAgreementName       string    `json:"order_agreement_name" xorm:"VARCHAR(128)"`
	OrderReview              int       `json:"order_review" xorm:"INT(11)"`
	OrderNreview             int       `json:"order_nreview" xorm:"INT(11)"`
	OrderIfRenew             int       `json:"order_if_renew" xorm:"INT(11)"`
	TypeId                   int       `json:"type_id" xorm:"INT(11)"`
	OrderRebateAmount        string    `json:"order_rebate_amount" xorm:"DECIMAL(20,3)"`
	OrderCancelNode          int       `json:"order_cancel_node" xorm:"default 0 INT(3)"`
	OrderRelationOrderId     string    `json:"order_relation_order_id" xorm:"TEXT"`
	OrderTransferName        string    `json:"order_transfer_name" xorm:"VARCHAR(225)"`
	OrderChangeOut           int       `json:"order_change_out" xorm:"INT(11)"`
	OrderChangeInto          int       `json:"order_change_into" xorm:"INT(11)"`
	OrderOutExamine          int       `json:"order_out_examine" xorm:"INT(11)"`
	OrderChangeIntoMoney     string    `json:"order_change_into_money" xorm:"DECIMAL(20,3)"`
	OrderTransferState       int       `json:"order_transfer_state" xorm:"INT(11)"`
	OrderTransferDate        time.Time `json:"order_transfer_date" xorm:"DATE"`
	OrderIsCancel            int       `json:"order_is_cancel" xorm:"default 1001 INT(11)"`
	OrderPassid              int       `json:"order_PassID" xorm:"INT(11)"`
	OrderCustomerType        int       `json:"order_customer_type" xorm:"INT(11)"`
	OrderRmbType             int       `json:"order_rmb_type" xorm:"INT(11)"`
	OrderRmbAmount           string    `json:"order_rmb_amount" xorm:"DECIMAL(20,3)"`
	OrderRmbHandlingcharge   int       `json:"order_rmb_handlingcharge" xorm:"INT(11)"`
	OrderRmbPayMethod        int       `json:"order_rmb_pay_method" xorm:"INT(11)"`
	OrderRmbBank             int       `json:"order_rmb_bank" xorm:"INT(11)"`
	OrderState               int       `json:"order_state" xorm:"INT(11)"`
	OrderAccountType         int       `json:"order_account_type" xorm:"INT(11)"`
	OrderAccountName         string    `json:"order_account_name" xorm:"VARCHAR(128)"`
	OrderAccountNumber       int       `json:"order_account_number" xorm:"INT(11)"`
	OrderBankName            string    `json:"order_bank_name" xorm:"VARCHAR(128)"`
	OrderBankCountry         int       `json:"order_bank_country" xorm:"INT(11)"`
	OrderPaymentNumber       string    `json:"order_payment_number" xorm:"VARCHAR(128)"`
	OrderRmbReason           string    `json:"order_rmb_reason" xorm:"VARCHAR(225)"`
	OrderRmbPerson           int       `json:"order_rmb_person" xorm:"INT(11)"`
	OrderRmbUser             int       `json:"order_rmb_user" xorm:"INT(11)"`
	OrderRmbFinanceId        int       `json:"order_rmb_finance_id" xorm:"INT(11)"`
	OrderPrepayCompany       int       `json:"order_prepay_company" xorm:"INT(11)"`
	OrderSaleSource          int       `json:"order_sale_source" xorm:"INT(11)"`
	OrderTransferReason      string    `json:"order_transfer_reason" xorm:"TEXT"`
	OrderRebatesType         int       `json:"order_rebates_type" xorm:"INT(11)"`
	OrderRelationOrderAll    string    `json:"order_relation_order_all" xorm:"TEXT"`
	OrderRebatesState        int       `json:"order_rebates_state" xorm:"INT(11)"`
	OrderRebatesRemark       string    `json:"order_rebates_remark" xorm:"TEXT"`
	OrderDepositRemark       string    `json:"order_deposit_remark" xorm:"VARCHAR(225)"`
	OrderDepositPaytype      int       `json:"order_deposit_paytype" xorm:"INT(11)"`
	OrderSource              int       `json:"order_source" xorm:"INT(11)"`
	OrderAid                 string    `json:"order_aid" xorm:"VARCHAR(128)"`
	OrderTypein              int       `json:"order_typein" xorm:"INT(11)"`
	OrderCancelOrderId       int       `json:"order_cancel_order_id" xorm:"INT(11)"`
	OrderCost400             string    `json:"order_cost_400" xorm:"DECIMAL(20,2)"`
	OrderCostDesign          string    `json:"order_cost_design" xorm:"DECIMAL(20,2)"`
	OrderCostDsf             string    `json:"order_cost_dsf" xorm:"DECIMAL(20,2)"`
	OrderCostOutsourcing     string    `json:"order_cost_outsourcing" xorm:"DECIMAL(20,2)"`
	OrderCostDisk            string    `json:"order_cost_disk" xorm:"DECIMAL(20,2)"`
	OrderCostSms             string    `json:"order_cost_sms" xorm:"DECIMAL(20,2)"`
	OrderCostDomain          string    `json:"order_cost_domain" xorm:"DECIMAL(20,2)"`
	OrderIsCrm               int       `json:"order_is_crm" xorm:"INT(11)"`
	OrderAgreementNote       string    `json:"order_agreement_note" xorm:"TEXT"`
	OrderRemarks             string    `json:"order_remarks" xorm:"TEXT"`
	OrderSubsidiary          int       `json:"order_subsidiary" xorm:"INT(11)"`
	OrderTripartiteAgreement string    `json:"order_tripartite_agreement" xorm:"VARCHAR(255)"`
}
