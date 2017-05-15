//
//  main.cpp
//  Poxemon
//
//  Created by Sean D. Sollé on 27/04/2017.
//  Copyright © 2017 Sean D. Sollé. All rights reserved.
//

#include <iostream>
#include <string.h>
#include <math.h>
#include "poxemon.h"

/******************************************************************************

 bool poxdecode( const char* const codestring,
 bool& bVaccinated,
 unsigned int& infections,
 unsigned int& deaths,
 unsigned int& code )

 Author:	Sean D. Sollé
 Created:	2017/04/27

 Purpose:	Decode a three character string representing vaccinations, infections and deaths back to those components.
 Inputs:	Code string
 Outputs:   Player vaccination status, number of deaths & infections, integer value outcome.

 Returns:   true if decoding successful.

 ******************************************************************************/

bool poxdecode(const char* const codestring, bool& bVaccinated, unsigned int& infections, unsigned int& deaths, unsigned int& code )
{
    // Alphabet is #defined in poxemon.h
    char alphabet[] = ALPHABASE22;

    // We are expecting exactly three character in our codestring.
    if ( strlen(codestring) != 3)
    {
        // Not a valid input code.
        return false;
    }

    // We're converting the codestring into this interger.
    code = 0;

    unsigned int index = 0;

    // Read the input string from left to right.
    while (index < 3)
    {
        // Look for the input character in our alphabet.
        char* pFound = strchr(alphabet,codestring[index]);

        if (pFound == NULL)
        {
            // Character not in alphabet - exit.
            return false;
        }

        // Convert from base 22 digit to integer representation.
        code = code*22 + int(pFound - alphabet);

        // Move one place to the right of the input string.
        index++;
    }

    // By default, player is unvaccinated.
    bVaccinated = false;

    // The second table of codes contains vaccinated players - the offset value is defined in poxemon.h
    if (code >= VACC_OFFSET)
    {
        // Reset to counting-from-zero.
        code-= VACC_OFFSET;

        // Player is vaccinated.
        bVaccinated = true;
    }

    // The infection number is the row with the nearest triangle number to our code.
    infections = (sqrt(8*code + 1) - 1)/2;

    // Re-calculate the nearest triangle number from the row.
    unsigned int triangle = (infections * (infections + 1)) / 2;

    // The triangle number is the row we're starting at, and the deaths are the offset into that row.
    deaths = code - triangle;

    return true;
}

/******************************************************************************

 int TestAllOutcomes()

 Author:	Sean D. Sollé
 Created:	2017/04/28

 ******************************************************************************/

int TestAllOutcomes()
{
    // Default code to pass into encoding function.
    char code[] = "???";

    // Column headings.
    std::cout << "i\td\tcode\t[decoded]\tcode\[decoded]\n\n";

    // Test every possible outcome.
    for (unsigned int infections=0; infections <= 99; infections++)
    {
        for (unsigned int deaths=0; deaths <= infections; deaths++)
        {
            for (unsigned int v=0; v <= 1; v++)
            {
                bool vaccinated = bool(v);

                // Generate code for unvaccinated players.
                poxencode(vaccinated, infections, deaths, code);

                // Only output the infections & deaths once.
                if (v==0)
                {
                    std::cout << infections << "\t" << deaths;
                }

                std::cout << "\t" << code;


                // Convert the code back into explicit values again!
                unsigned int i, d, o;

                bool bSuccess = poxdecode(code, vaccinated, i, d, o);

                if (bSuccess && vaccinated==v && infections==i && deaths==d)
                {
                    std::cout << "\t[" << vaccinated << "\t" << i << "\t" << d << '\t' << o << "]";

                }
                else
                {
                    // Verify failed, exit with error
                    std::cout << "\t***** ERROR decoding this row! ****";
                    return -1;
                }
            }

            std::cout << "\n";
        }
    }

    // Everything worked fine.
    return 0;
}

/******************************************************************************

 int main(int argc, const char * argv[])

 Author:	Sean D. Sollé
 Created:	2017/04/11

 ******************************************************************************/


int main(int argc, const char * argv[])
{
    // Default code to pass into encoding function.
    char code[] = "???";

    unsigned int infections, deaths, outcome;
    bool vaccinated = false;

    // Subtracting one to ignore the system-supplied default,
    // how many argugments have been passed?
    switch (argc-1)
    {
            // Single paramater passed - assume it's a three-letter code to decode.
        case 1:
        {
            // Copy the first three letters from user input to the code string as upper case.
            code[0] = toupper(argv[1][0]);
            code[1] = toupper(argv[1][1]);
            code[2] = toupper(argv[1][2]);


            if (!poxdecode(code, vaccinated, infections, deaths, outcome))
            {
                std::cout << "Sorry, couldn't decode " << code << "\n\n";
                return -1;
            }

            break;
        }

            // Three params - deaths, infections, vaccinated.
        case 3:
        {
            vaccinated = true;
        }

            // Three params falls through to here.
        case 2:
        {
            infections = atoi(argv[1]);
            deaths = atoi(argv[2]);

            poxencode(vaccinated, infections, deaths, code);
            break;
        }

            // Any other values, assume we want to test all params.
        default:
        {
            return TestAllOutcomes();
        }

    }

    std::cout << "I/P\tDeaths\tVacc?\tCode\n";
    std::cout << infections << '\t' << deaths  << '\t' << vaccinated << '\t' << code << "\n\n";

    return 0;
}
