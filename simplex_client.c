/*
 * Name: Andrew Pierson
 * Course-Section: CS-440
 * Assignment: Assignment 1
 * Due Date: 02/16/24
 * Collaborators: None
 * Sources: textbook, simplex_server
 * Description: Client implimentation by using BSD socket API by receiving quotes from server.
 */
#include <sys/types.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <netdb.h>

#include <err.h>
#include <stdio.h>
#include <string.h>
#include <unistd.h>
const int SERVER_PORT = 8017;
const int MAX_LINE =    256;

int
main(int argc, char *argv[])
{
    struct sockaddr_in sin; 
    char buf[MAX_LINE]; 
    struct hostent *hp; 
    char *host; 
    int s; 

    /*Process command line arguemnts*/
    if (argc == 2)
        host = argv[1];
    else
        errx(1,"usage: client hostname");

    /*ask resolver for ip adress of hostname*/
    hp = gethostbyname(host);
    if (!hp)
        errx(1, "Unkown host: %s", host);

    /*build address structure*/
    memset((char*)&sin, '\0', sizeof sin);
    sin.sin_family= AF_INET;
    memcpy((char*)&sin.sin_addr.s_addr, hp->h_addr,hp->h_length);
    sin.sin_port = htons(SERVER_PORT);
    
    /*build address data structure*/	
    if ((s = socket(AF_INET, SOCK_STREAM,0)) == -1)
        err(1, "unable to open socket");
    
    /*begin active open*/
    if ((s = socket(PF_INET, SOCK_STREAM,0)) == -1)
        err(1, "unable to open socket");

    /*connect to socket*/
    if (connect(s, (struct sockaddr*)&sin, sizeof sin) == -1) {
        close(s);
        err(1,"connect failed");
    }

    /*get and display the quote from server*/
    memset(buf, 0, sizeof buf);
    if (recv(s, buf, sizeof(buf)-1, 0)<0)
        err(1, "unable to receive quote");
    else
        fputs(buf, stdout);
    
    close(s);
    return 0;
}

