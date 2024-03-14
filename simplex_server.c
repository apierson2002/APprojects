/*
 * Name: Andrew Pierson
 * Course-Section: CS-440
 * Assignment: Assignment 1 
 * Due Date: 02/21/24
 * Collaborators: None
 * Resources: textbook, simplex_client
 * Description: Server implimentation by using BSD socket API by sending quotes to a client.
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
const int MAX_PENDING = 5;
const int MAX_LINE =    256;

int
main(void)
{
    struct sockaddr_in sin; 
    int s,new_s; 
    char buf[MAX_LINE]; 
    socklen_t len; 

    /*Build address data structure*/    
    if ((s = socket(AF_INET, SOCK_STREAM,0)) == -1)
        err(1, "unable to open socket");
    
    /*build address structure*/
    memset((char*)&sin, '\0', sizeof sin);
    sin.sin_family= AF_INET;
    sin.sin_addr.s_addr = INADDR_ANY;
    sin.sin_port = htons(SERVER_PORT);

    /*begin passive open*/    
    if ((s = socket(AF_INET, SOCK_STREAM,0)) == -1)
        err(1, "unable to open socket");
    if ((bind(s, (struct sockaddr*)&sin, sizeof sin)) == -1)
        err(1, "unable to bind socket");
    if ((listen(s,MAX_PENDING)) == -1)
        err(1,"listen on socket failed");
    
    /*open quotes file*/
    FILE *file;
    file = fopen("quotes.txt", "r"); 
    while (1){
        len= sizeof sin;
        if ((new_s = accept(s, (struct sockaddr*)&sin, &len)) == -1){
            close(s);
            err(1, "accept failed");
        }
        /*send the 1 quote and get from file*/
        memset(buf, 0, sizeof buf);
        if (fgets(buf, sizeof buf, file)){
            len = strnlen(buf, sizeof buf)+1;
            /*fputs(buf, stdout);*/
            send(new_s, buf, len,0);
            memset(buf, 0, sizeof buf);
        } else {
            err(1, "No messeges");
        }
        close(new_s);
    }
    
    /*not reached*/
    close(s);
    return 0;
}




