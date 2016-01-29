package model

type TcApiDelBak struct {
	OrderId       string `json:"order_id" xorm:"VARCHAR(128)"`
	Salespay      string `json:"salespay" xorm:"TEXT"`
	Rebate        string `json:"rebate" xorm:"TEXT"`
	Invoice       string `json:"invoice" xorm:"TEXT"`
	OrderD        string `json:"order_d" xorm:"TEXT"`
	Refund        string `json:"refund" xorm:"TEXT"`
	RelOrderOrder string `json:"rel_order_order" xorm:"TEXT"`
	Order         string `json:"order" xorm:"TEXT"`
}
