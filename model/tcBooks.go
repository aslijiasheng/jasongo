package model

import (
	"time"
)

type TcBooks struct {
	BooksId           int       `json:"books_id" xorm:"not null pk autoincr INT(11)"`
	BooksCompany      int       `json:"books_company" xorm:"not null INT(11)"`
	BooksBankAccount  int       `json:"books_bank_account" xorm:"INT(11)"`
	BooksAdate        time.Time `json:"books_adate" xorm:"DATETIME"`
	BooksBdate        time.Time `json:"books_bdate" xorm:"DATETIME"`
	BooksTradeNo      string    `json:"books_trade_no" xorm:"VARCHAR(128)"`
	BooksAttnId       int       `json:"books_attn_id" xorm:"INT(11)"`
	BooksDepartmentId int       `json:"books_department_id" xorm:"INT(11)"`
	BooksMemo         string    `json:"books_memo" xorm:"VARCHAR(225)"`
	BooksCustomerInfo string    `json:"books_customer_info" xorm:"VARCHAR(128)"`
	BooksSaleNo       string    `json:"books_sale_no" xorm:"VARCHAR(128)"`
	BooksOrderNo      string    `json:"books_order_no" xorm:"VARCHAR(128)"`
	BooksInvoiceTrack int       `json:"books_invoice_track" xorm:"INT(11)"`
	BooksRate         string    `json:"books_rate" xorm:"DECIMAL(20,3)"`
	BooksRmb          string    `json:"books_rmb" xorm:"DECIMAL(20,3)"`
	BooksState        int       `json:"books_state" xorm:"INT(11)"`
	BooksCreatetime   time.Time `json:"books_createtime" xorm:"DATETIME"`
	BooksUpdatetime   time.Time `json:"books_updatetime" xorm:"DATETIME"`
	BooksNote         string    `json:"books_Note" xorm:"VARCHAR(225)"`
	BooksDebitAmount  string    `json:"books_debit_amount" xorm:"DECIMAL(20,3)"`
	BooksCreditAmount string    `json:"books_Credit_amount" xorm:"DECIMAL(20,3)"`
	BooksCashier      int       `json:"books_cashier" xorm:"INT(11)"`
	BooksAccounting   int       `json:"books_accounting" xorm:"INT(11)"`
	BooksSalesNo      string    `json:"books_sales_no" xorm:"VARCHAR(128)"`
	BooksOtherMemo    string    `json:"books_other_memo" xorm:"VARCHAR(225)"`
	BooksNumber       string    `json:"books_number" xorm:"VARCHAR(128)"`
	BooksSubsidiary   int       `json:"books_subsidiary" xorm:"INT(11)"`
}
