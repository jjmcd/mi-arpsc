#!/bin/sh
DS="1 2 3 5 6 7 8"
NN=3
for D in $DS ; do
  AL=`expr 500 '*' $NN`
  echo "INSERT INTO \`textblocks\` VALUES(15100,$AL,'<hr><h2>District $D</h2>',now());"
  NN=`expr $NN + 1`
done
NW="NWS-APX NWS-DTX NWS-GRR NWS-MQT"
for D in $NW ; do
  AL=`expr 500 '*' $NN`
  echo "INSERT INTO \`textblocks\` VALUES(15100,$AL,'<hr><h2>$D</h2>',now());"
  NN=`expr $NN + 1`
done


