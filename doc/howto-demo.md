Preparation
-----------
open a terminal window and type:
    
    cd ~/var/log
    tail -f dev.log | grep app.INFO

- login with 3 different users: admin, content and foto
- with user content create a new item (Dinge)
- with user content push item through states until foto
- with user foto push item through states
- with user content reject publishing and continue with user foro
- with user content push item to published

- Repeat exactly the same with a new stuff (Zeug)

- compare - evething should be identical except stateschemas in items

Questions:
- how to change persmissions?
- how to integrate a new State?
- how to change behaviour?
