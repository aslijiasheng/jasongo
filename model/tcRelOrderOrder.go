package model

type TcRelOrderOrder struct {
	RelOrderOrderId       int `json:"rel_order_order_id" xorm:"not null pk autoincr INT(11)"`
	RelOrderOrderRelOrder int `json:"rel_order_order_rel_order" xorm:"INT(11)"`
	RelOrderOrderMyOrder  int `json:"rel_order_order_my_order" xorm:"INT(11)"`
}
