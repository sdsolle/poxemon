//
//  main.cpp
//  Poxemon
//
//  Created by Sean D. Sollé on 27/04/2017.
//  Copyright © 2017 Sean D. Sollé. All rights reserved.
//

#include <iostream>
#include "poxemon.h"

int main(int argc, const char * argv[])
{
    // Default code to pass into encoding function.
    char code[] = "???";

    for (unsigned int infections=0; infections <= 99; infections++)
    {
        for (unsigned int deaths=0; deaths <= infections; deaths++)
        {
            // Generate code for unvaccinated players.
            poxencode(false, infections, deaths, code);

            std::cout << infections << "\t" << deaths << "\t" << code;

            // Generate code for vaccinated players.
            poxencode(true, infections, deaths, code);

            std::cout << "\t" << code << "\n";
        }
    }

    return 0;
}
