/* 
Product data delete in Prize Check Grab The Chance 
Invoice Check Amount status in sub promotion 


1. Find Active Promotions
2. Find all Products of Invoices
3. Find Sub Promotions based on promotion
4. Find Invoice Check Type

valid_data[]
sub_valid_data[]

Loop Invoice Check Type

 	if  Sub Promotions Promoiton 's Invoice Check Type = 1 //Amount{
 		calculate ticket qty based on total_valid_amount / amount_checks.amount 
 		sub_valid_data[
 			promtion_name
 			sub_promtion_name
 			valid_qty
 		]
 	}
 	if  Sub Promotions Promoiton 's Invoice Check Type = 2 // Product{	
 		
		Find Promotion Product in all Product of Invoices
		calculate total_valid_amount again
		Calculate Valid qty form based on total_valid_amount/ prize_ticket_check.ticket_prize_amount
 			sub_valid_data[
	 			promtion_name
	 			sub_promtion_name
	 			valid_qty
	 		]
 	}

valid_data[] =	sub_valid_data

*/
