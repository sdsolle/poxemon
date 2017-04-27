//
//  poxemon.cpp
//
//  Created by Sean D. Sollé on 11/04/2017.
//  Copyright © 2017 Sean D. Sollé. All rights reserved.
//

#include <iostream>
#include <string.h>

/******************************************************************************

    void encode( unsigned int infections, unsigned int deaths, char* output)

	Author:		Sean D. Sollé
	Created:	2017/04/11

	Purpose:	Fill a three character string with a code representing vaccinations, infections and deaths.
 	Inputs:		Output character string, player vaccination status, number of deaths & infections.
    Outputs:    Poxemon code, a three character string.

    Description:

    Encoding vaccination status, infections and deaths into a single code is a two-step process.

    STEP 1:

    Calculate the 'outcome' number.

    For each infection N, N+1 deaths are possible, giving N(N+1)/2 possible outcomes.

    So with 0 to 99 possible infections, there are 5050 possible outcomes:

    Infections    Deaths    Outcome
    0           0           0
    1           0           1
    1           1           2
    2           0           3
    2           1           4
    2           2           5
    3           0           6
    3           1           7
    ...         ...
    99          96          5046
    99          97          5047
    99          98          5048
    99          99          5049

    (See poxemon.csv for the complete list).

    If we arrange the number of deaths in a triangle, with one row per infection ...

    0
    0 1
    0 1 2
    0 1 2 3

    ...

    0 1 2 3  ..   97
    0 1 2 3  ...  97 98
    0 1 2 3  .... 97 98 99


    ... then the row at which 'N' infections start is the triangle number of 'N'.

    This means the outcome number is simply the row number + number of infections.

    If the player is vaccinated, we start the outcome numbering at a value greater than 5049.

    STEP 2:

    By encoding the outcome number in a higher base, we allow each of the 10100
    possibilites to be represented with just three characters.

    Since 22^3 = 10648, we can represent all our outcome values using base 22.

 ******************************************************************************/


void encode(unsigned int infections, unsigned int deaths, char* output)
{

    //  For each infection N, N+1 deaths are possible, giving N(N+1)/2 possible outcomes.
    //  So a triangle number translates directly to the row at which 'N' infections start.

    unsigned int triangle = (infections * (infections + 1)) / 2;

    // Since we know what row we're starting at, we just add the deaths to find the actual code.

    unsigned int code = triangle + deaths;   // + 5050; // If vaccinated.

    // Note that we don't use zero (to ensure our codes start from '111')
    // and we've not included 'I' (to avoid being mistaken for '1').

    char alphabet[] = "123456789ABCDEFGHJKLMN";

    // With 22 characters, we're encoding in base 22.

    unsigned int base = int(strlen(alphabet));

    // We want to write from the rightmost character (i.e. the least significant digit), and move left.
    // So we start with our index pointing at the terminating null ...

    int index = int(strlen(output));


    // If we've been passed an empty string, we never enter this loop.
    while (index > 0)
    {
        // There's at least one character in the output, so we move one place to the left to overwrite it.
        index--;

        // Store the leastmost significant digit.
        output[index] = alphabet[code % base];

        // And shift the value we're converting one base N place to the right.
        code = code/base;
    }
}

int main(int argc, const char * argv[])
{
    // Default code to pass into encoding function.
    char code[] = "???";

    // Loop over every possible infection and death outcome.

    for (unsigned int infections=0; infections <= 99; infections++)
    {
        for (unsigned int deaths=0; deaths <= infections; deaths++)
        {

            encode(infections, deaths, code);

            std::cout << infections << "\t" << deaths << "\t" << code << "\n";

        }
    }

    return 0;
}

