//
//  svg.cpp
//  Poxemon
//
//  Created by Sean D. Sollé on 22/08/2017.
//  Copyright © 2017 Sean D. Sollé. All rights reserved.
//

#include <iostream>
#include <string.h>
#include <math.h>
#include "poxemon.h"
#include "svg.h"


/******************************************************************************

 int RenderSVG()

 Author:	Sean D. Sollé
 Created:	2017/08/22

 ******************************************************************************/

int RenderSVG()
{

    // Default code to pass into encoding function.
    char code[] = "???";

    std::cout << "<svg viewBox='0 -100 10000 25100' xmlns='http://www.w3.org/2000/svg'>\n";


    // Test every possible outcome.
    for (unsigned int v=0; v <= 1; v++)
    {

        bool vaccinated = bool(v);

        if (vaccinated)
        {
            std::cout << "<g fill='none' stroke='black'>\n";
        }
        else
        {
            std::cout << "<g fill='black' stroke='black'>\n";
        }

        for (unsigned int infections=0; infections <= 99; infections++)
        {
            for (unsigned int deaths=0; deaths <= infections; deaths++)
            {
                // Generate code for player.
                poxencode(vaccinated, infections, deaths, code);

                int x = (infections - deaths) * 100;
                int y = (infections * 100) + (vaccinated * 12500);

                std::cout << "<circle cx='" << x << "' cy='" << y << "' r='40'";
/*
                if (vaccinated)
                {
                    std::cout << " fill='none'";
                }
*/
                std::cout << "/>";

// text attribute                std::cout << "\t" << code;


                std::cout << "\n";

            }
            
        }
        std::cout << "</g>\n";
    }

    std::cout << "</svg>\n";


    // Everything worked fine.
    return 0;
}

