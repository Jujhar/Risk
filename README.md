<em>Work In Progress - Unfortunately only basic movement is available, attack logic and multiplayer play still todo.</em>
# Risk

A single player / multiplayer (todo) online board game.

If you played the classic version of this game then forget all those rules this game has no resemblance to those rules.

# Development
## Run locally
copy contents of "index.html.dev" to "index.html"

vite dev

## Basic rules
![image](https://user-images.githubusercontent.com/5521110/204102056-403e519b-bc14-4f45-8e49-a82d5446fb0b.png)

At the beginning of the turn roll the dice (seen above) by quickly selecting a number using keypad to see how many movements you can advance.

Dotted water ways between countries just show where the adjecent moves are and make no difference to the movement of spaces consumed.

```
For example if you roll 3 you could pass 1 person up 3 countries,

or you can alternatively move 2 groups of people - 1 up 1 country and another up 2

or 3 groups of people 1 country each.
```

## Battles
The main gist of the battles are that 2 people will kill 1, 3 will kill 2.

Now this rule of greater units equal win wont doesn't apply to Generals. So two Generals will not kill one General rather here each general represents a dice roll you get and you win with the highest dice role. So it would be the person with two Generals will get two dice rolls whereas the other person will only get one dice roll. If someone has 5 Generals then they get 5 dice rolls.

If it is a tie then nothing happens but a movement is consumed anyways.

![image](https://user-images.githubusercontent.com/5521110/204102153-71f96483-5238-4f10-a36f-ea7f3b773bf4.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 infantry = 1 unit


A General is worth 10 units so 9 people will get killed by a General but 11 individual people will kill 1 General.

![image](https://user-images.githubusercontent.com/5521110/204102284-d3a2f84d-7cd4-41cd-800c-4a4abe57aa75.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 General = 10 units



### Cannons

![image](https://user-images.githubusercontent.com/5521110/204102417-b7763c7a-4fd8-46ed-a3f8-28c45c3f9798.png)
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 cannon = 0 units strength *but 5 units to purchase

Cannons are worth 0 units so 1 enemy infantry or 1 General can easily take over a cannon.
Firing a cannon takes 0 turns however a cannon could only fire twice per turn.
A cannon fires by killing one infantry on an adjacent country.
A cannon does no damage to Generals or other cannons.

```
In summary for initial game startup purposes or reward points after conquering entire continent

1 infantry is worth 1 point

1 General is worth 10 points

1 cannon is worth 5 points
```

## Continental conquest.
If you have a unit in each of the spaces of a continent than you get that amount of units listed below at the start of your turn which you can place anywhere on your territories.


![image](https://user-images.githubusercontent.com/5521110/204102573-5a907c27-ddff-4606-93c8-29f5eefe8ce2.png)

## Cards and treasures.

Each card is marked with a country and an unit to be gained (eg infantry, General or cannon) when you reach that country than you will get that unit on that card and also pick up another card. The unit you gained from that card can be placed on any territory you own (where you have other units on).

If you get a wild card, showing all 3 unit types and no territory than you get 1 of each (infantry, General, and cannon), as well as another card.




## Todo - Decision Record
# How to share data between nodes

## Status

What is the status, such as proposed, accepted, rejected, deprecated, superseded, etc.?

## Context

What is the issue that we're seeing that is motivating this decision or change?

## Decision

What is the change that we're proposing and/or doing?

## Consequences

What becomes easier or more difficult to do because of this change?
