konomi web app
==============

Useful database queries
-----------------------

# Sum normal expends without fuel
    SELECT SUM(sum) FROM log WHERE type=0 AND code!='fuel' AND createstamp>='2016-05-01' AND createstamp<='2016-05-31';

# Sum fuel expends
    SELECT SUM(sum) FROM log WHERE type=0 AND code='fuel' AND createstamp>='2016-05-01' AND createstamp<='2016-05-31';

# Sum fix expends
    SELECT SUM(sum) FROM log WHERE type=-1 AND createstamp>='2016-05-01' AND createstamp<='2016-05-31';

