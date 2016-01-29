package model

type TcBgrelation struct {
	BgrelationId           int    `json:"bgrelation_id" xorm:"not null pk autoincr INT(11)"`
	BgrelationBundleId     int    `json:"bgrelation_bundle_id" xorm:"INT(11)"`
	BgrelationGoodsId      int    `json:"bgrelation_goods_id" xorm:"INT(11)"`
	BgrelationGoodsPriceId int    `json:"bgrelation_goods_price_id" xorm:"INT(11)"`
	BgrelationGroupPrice   string `json:"bgrelation_group_price" xorm:"DECIMAL(20,3)"`
}
