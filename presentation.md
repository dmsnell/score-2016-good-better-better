



```
{
  "title": "Good Better Better",
  "description": "A discussion on code quality and improvement.",
  "author": "Dennis Snell <dennis.snell@automattic.com>",
  "event": "WordCamp Orlando 2016"
}
```




---







Q. What makes for good code?







---







A. Good code is the result of a multi-variate optimization problem.







---




## Constraints in developing software

 - Budget
 - Developer time
 - Memory size
 - Processing power
 - Cognitive abilities
 - User frustration quotient




---





We have the most context for what we are doing when we do it.
Each hour that passes _after_ doing it means that we lose that context and will have to spend time to pick it up again.





---




## Watch out for these

 - Assumptions
 - Shared state and stateful operations
 - Type errors
 - Long and confusing functions





---





## Pursue these

 - Communication (Early)
 - Communication (Often)
 - Communication (Efficiently)





---





## Case study: LM386

 - Two inputs, one output
 - Block diagram
 - Drop-in replacements





---





## Example

 - Complection
 - Lots of error-handling
 - Variables hold different _values_ at different times
 - Variables hold different _types_ at different times
 - Lack of testability





---



## Comments

**Comments are there for what's not**

 - Abnormalities
 - Rationale for adopting one choice over another
 - Non-obvious risks
 - Expected inputs and outputs



---




## Food for thought

 - If you have to pause to think about it, it's not obvious.

 - Don't be cute. The best programmers prove themselves by writing extremely simple code.

 - Be suspicious of `if`s and `for`s and `while`s and friends. They appear safe enough but hide intention.




---





## Summary

 - Can you debug it?
 - Can you test it?
 - Can you change it?
 - Can you communicate it?





---
