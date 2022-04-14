# E-wallet-banking-transactions
Listen and write every banking transaction. This source based on Rezer API, created by github.com/takashato and i just simplize for PHP usage with a sample for e-wallet MOMO

*Env:
- xampp, PHP
*Database:
- MySQL

*Step by step:
- How to set up API -> "Rezer - Login and Register API Turtorial.docx"  
- file "banktransfer.php" - file Callback URL , when we have transactions in registered banking account, it will call and send transaction data.  
- file "addBankTransfer.php" - file process of reading transaction data after checking validation from "banktransfer.php", then save data to database (it has my example source code to update balance(wallet/point) for a customer)  
- file "templateData.json" - Descript a sample POST data to callback API.
