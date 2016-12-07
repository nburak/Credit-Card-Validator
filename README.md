This project will help you to validate credit cards of your customers. You will be sure about the card's Suitability to rules. This project includes an virtual pos function. It may be used, if it is customized with your information. But it's not recommended to use this function for sales. You should use your bank's own api for virtual pos. Because of every bank to have different parameters and informations than others. 

Card Number Validation(Luhn Algorithm)
You only post request from cards which are suitable to rules with this plugin. So you will block unnecessary traffic and increase your site's performance. Because every credit card numbers have an algorithm and if a card number is not suitable to this algorithm, it won't even post the request to the bank.

Security Code Validation(CVV,CID)
Banks give a code which is hashed with an unknown algorithm to understand the card's reality to the card. This code is called as CVV for Visa,Master,Discover,JCB and CID for American Express. 
CVV must have 3 digits and CID must have 4 digits. 
Plugin make validation of security code and if it's digit count is not correct for it's card type, it will block the process and not post the request to the bank.

Expiration Date Validation
Plugin will validate the card's expiration date and if it's expired, request won't be posted to the bank.

Type Validation
Every card type such as Master Card , Amex, Visa... starts with a specific number. This project will understand the type of the card and if it's not supported, request won't be posted to the bank.
