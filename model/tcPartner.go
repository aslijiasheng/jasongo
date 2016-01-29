package model

import (
	"time"
)

type TcPartner struct {
	PartnerId              int       `json:"partner_id" xorm:"not null pk autoincr INT(11)"`
	PartnerName            string    `json:"partner_name" xorm:"VARCHAR(128)"`
	PartnerShopexId        string    `json:"partner_shopex_id" xorm:"VARCHAR(50)"`
	PartnerDepartment      int       `json:"partner_department" xorm:"INT(11)"`
	PartnerManager         string    `json:"partner_manager" xorm:"VARCHAR(128)"`
	PartnerLevel           int       `json:"partner_level" xorm:"INT(11)"`
	PartnerDisc            string    `json:"partner_disc" xorm:"VARCHAR(128)"`
	PartnerCooperativetime time.Time `json:"partner_CooperativeTime" xorm:"DATETIME"`
	PartnerEndingdate      time.Time `json:"partner_endingdate" xorm:"DATETIME"`
	PartnerContacts        string    `json:"partner_contacts" xorm:"VARCHAR(128)"`
	PartnerEmail           string    `json:"partner_email" xorm:"VARCHAR(50)"`
	PartnerAddress         string    `json:"partner_address" xorm:"VARCHAR(128)"`
	PartnerFax             int       `json:"partner_fax" xorm:"INT(11)"`
	PartnerRegion          string    `json:"partner_region" xorm:"VARCHAR(128)"`
	PartnerContract        string    `json:"partner_contract" xorm:"VARCHAR(128)"`
	PartnerIsU8            int       `json:"partner_is_u8" xorm:"INT(11)"`
	PartnerPbiId           string    `json:"partner_pbi_id" xorm:"VARCHAR(128)"`
}
