import MySQLdb

db = MySQLdb.connect(host='localhost',
                      user='project',
                      passwd='password',
                      db='project')

cur = db.cursor()

cur.execute("INSERT INTO test VALUES ('Home',4,6)")
db.commit()

cur.execute('SELECT * FROM test')

for row in cur.fetchall() :
    print row[0]
    print row[1]