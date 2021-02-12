ToDo
====
* BUG: User Entity - delete prohibited by foreign key constraint
  
* BUG: On creating a Contact, the Contact also get a new Profile 
  -> Erzeugung des Profiles aus Domain/Bbs/Contact:add herausnehmen und 
     nach AccountCreatedSubscriber::onAccountCreatedEvent verschieben.

* BUG: Contact Entity - delete prohibited by foreign key constraint