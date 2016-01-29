package model

type TcPtrelation struct {
	PtrelationId             int `json:"ptrelation_id" xorm:"not null pk autoincr INT(11)"`
	PtrelationProductTechId  int `json:"ptrelation_product_tech_id" xorm:"not null INT(11)"`
	PtrelationProductBasicId int `json:"ptrelation_product_basic_id" xorm:"not null INT(11)"`
}
