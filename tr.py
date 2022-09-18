file = open("tr.txt","r")
file1 = open("tr1.txt","a")
lines = file.readlines()
for i in range(0,len(lines),3):
    j = i
    line1 = (lines[j])[0:len(lines[j])-1]
    line2 = (lines[j+1])[0:len(lines[j])-1]
    line3 = (lines[j+2])[0:len(lines[j])]
    print(line1+line2+line3)