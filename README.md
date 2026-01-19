> [!WARNING]
> ## Read Cautiously! 
> This project should have the flows consist of all of this:

``pgsql
User
 └─ Upload PDF
     └─ Create Print Job (PENDING)
         └─ Generate QRIS Invoice
             └─ WAIT PAYMENT
                 └─ Payment Callback (SERVER-TO-SERVER)
                     └─ VERIFY SIGNATURE
                         └─ Update Job → PAID
                             └─ Push to Print Queue
                                 └─ Printer Daemon Polling
                                     └─ Print
                                         └─ Update Job → DONE
``
