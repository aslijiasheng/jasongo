package model

import (
	"time"
)

type TcSynU8Voucher struct {
	SynU8VoucherId         int       `json:"syn_u8_voucher_id" xorm:"not null pk autoincr INT(11)"`
	SynU8VoucherOrder      int       `json:"syn_u8_voucher_order" xorm:"INT(11)"`
	SynU8VoucherSynDate    time.Time `json:"syn_u8_voucher_syn_date" xorm:"DATETIME"`
	SynU8VoucherQueryXml   string    `json:"syn_u8_voucher_query_xml" xorm:"TEXT"`
	SynU8VoucherResultXml  string    `json:"syn_u8_voucher_result_xml" xorm:"TEXT"`
	SynU8VoucherQueryTime  time.Time `json:"syn_u8_voucher_query_time" xorm:"DATETIME"`
	SynU8VoucherResultTime time.Time `json:"syn_u8_voucher_result_time" xorm:"DATETIME"`
}
