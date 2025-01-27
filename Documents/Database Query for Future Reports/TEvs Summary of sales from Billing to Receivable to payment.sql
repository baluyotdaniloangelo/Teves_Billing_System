/*
This Query Performs to check the 3 Following tables
teves_billing_table
JOIN teves_receivable_table
JOIN teves_receivable_payment

from billing to receivable and receivable payment
this can check the product total sales based from deduction from receivable discount and tax



*/
SELECT 
b.billing_id,
a.product_name,
a.product_unit_measurement,
b.order_quantity,
b.order_total_amount,
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END)) AS liters_discount,

/*ROUND((b.order_total_amount / c.receivable_net_value_percentage),2) AS vat_sales,

ROUND(((b.order_total_amount / c.receivable_net_value_percentage) * (receivable_vat_value_percentage / 100)),2) AS vat_amount,

*/

ROUND(((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100)),2) AS with_holding_tax,

ROUND
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
,2)
AS total_discount_and_tax,

ROUND
(

b.order_total_amount
-
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
)


,2)
AS total_sales_billing_to_receivable,


SUM(d.receivable_payment_amount) AS sum_payment,

ROUND

(
(
(
b.order_total_amount
-
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
)
)
/ SUM(c.receivable_amount)
) /** 100*/

,2)
AS percentage_paid,

ROUND

(
(
(
b.order_total_amount
-
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
)
)
/ SUM(c.receivable_amount)
) 
* SUM(d.receivable_payment_amount)
/** 100*/

,2)
AS amount_paid,



ROUND
(1-
(
(
(
b.order_total_amount
-
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
)
)
/ SUM(c.receivable_amount)
) /** 100*/
)
,2)
AS percentage_unpaid,

ROUND
(1-
(
(
(
b.order_total_amount
-
(
(b.order_quantity * ( CASE WHEN a.product_unit_measurement = 'L' THEN c.less_per_liter ELSE 0 /*if non Liter(L) Return Zero*/ END))
+
((b.order_total_amount / c.receivable_net_value_percentage) * (c.receivable_withholding_tax_percentage / 100))
)
)
/ SUM(c.receivable_amount)
) 
* SUM(c.receivable_remaining_balance)
/** 100*/
)
,2)
AS amount_unpaid

FROM teves_product_table a
LEFT JOIN teves_billing_table b ON b.product_idx = a.product_id
LEFT JOIN teves_receivable_table c ON c.receivable_id = b.receivable_idx
LEFT JOIN teves_receivable_payment d ON d.receivable_idx = c.receivable_id

/**/
WHERE b.order_date BETWEEN '2023-12-01' AND '2023-12-01'
  AND b.client_idx = 9
  GROUP BY b.billing_id,a.product_id
  
/*
To Add column on Billing table

liters_discount
withholding_tax
total_sales_receivable
amount_paid_percentage
amount_paid
amount_unpaid_percentage
amount_unpaid

this column should be applied also to sales order component table

*/  
  /*
  
GROUP BY a.product_id,d.receivable_idx,b.receivable_idx,c.receivable_id*/