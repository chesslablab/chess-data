<?php

namespace ChessData\Utils;

class Username
{
    private $adjectives = [
        "abject",
        "abrupt",
        "absent",
        "aloof",
        "amuck",
        "amused",
        "angry",
        "aware",
        "bad",
        "bent",
        "big",
        "bitter",
        "bored",
        "boring",
        "bouncy",
        "brainy",
        "brash",
        "brave",
        "brawny",
        "breezy",
        "brief",
        "bright",
        "broad",
        "bumpy",
        "burly",
        "busy",
        "cagey",
        "calm",
        "capable",
        "careful",
        "caring",
        "certain",
        "chief",
        "chilly",
        "chunky",
        "clammy",
        "classy",
        "clean",
        "clear",
        "clever",
        "cloudy",
        "closed",
        "clumsy",
        "cold",
        "cool",
        "crabby",
        "craven",
        "crazy",
        "creepy",
        "cuddly",
        "curious",
        "curly",
        "curved",
        "curvy",
        "cute",
        "cynical",
        "daffy",
        "daily",
        "damp",
        "dapper",
        "dark",
        "dear",
        "deep",
        "deeply",
        "dirty",
        "dizzy",
        "drab",
        "dreary",
        "dry",
        "dull",
        "dusty",
        "eager",
        "early",
        "earthy",
        "easy",
        "elfin",
        "elite",
        "even",
        "exotic",
        "faded",
        "faint",
        "fair",
        "false",
        "famous",
        "fancy",
        "fast",
        "faulty",
        "feeble",
        "feigned",
        "festive",
        "fierce",
        "filthy",
        "fine",
        "fixed",
        "flaky",
        "flashy",
        "flimsy",
        "fluffy",
        "foamy",
        "foolish",
        "frail",
        "fragile",
        "frantic",
        "free",
        "fretful",
        "funny",
        "furry",
        "furtive",
        "future",
        "fuzzy",
        "gabby",
        "gainful",
        "gaping",
        "gaudy",
        "gentle",
        "giant",
        "giddy",
        "gifted",
        "glib",
        "glossy",
        "godly",
        "good",
        "goofy",
        "gratis",
        "greasy",
        "great",
        "greedy",
        "green",
        "grey",
        "groovy",
        "grouchy",
        "grubby",
        "grumpy",
        "guarded",
        "halting",
        "handy",
        "hapless",
        "happy",
        "hard",
        "harsh",
        "heady",
        "healthy",
        "heavy",
        "hollow",
        "homely",
        "huge",
        "hungry",
        "hurt",
        "hushed",
        "husky",
        "icky",
        "ill",
        "innate",
        "irate",
        "itchy",
        "jaded",
        "jagged",
        "jazzy",
        "jolly",
        "joyous",
        "jumpy",
        "keen",
        "kind",
        "knotty",
        "known",
        "lame",
        "large",
        "last",
        "late",
        "lavish",
        "lazy",
        "lean",
        "legal",
        "lethal",
        "level",
        "lewd",
        "light",
        "little",
        "lively",
        "living",
        "lonely",
        "long",
        "loose",
        "loud",
        "lovely",
        "loving",
        "lowly",
        "lucky",
        "lumpy",
        "lush",
        "lying",
        "madly",
        "marked",
        "mature",
        "mean",
        "measly",
        "medical",
        "meek",
        "mellow",
        "melodic",
        "melted",
        "mere",
        "messy",
        "mighty",
        "milky",
        "minor",
        "misty",
        "mixed",
        "moaning",
        "modern",
        "moldy",
        "murky",
        "mushy",
        "mute",
        "naive",
        "nappy",
        "narrow",
        "nasty",
        "natural",
        "naughty",
        "near",
        "neat",
        "needy",
        "nervous",
        "new",
        "next",
        "nice",
        "nifty",
        "nimble",
        "nippy",
        "noisy",
        "normal",
        "nosy",
        "nutty",
        "oafish",
        "odd",
        "old",
        "open",
        "oval",
        "overt",
        "pale",
        "paltry",
        "past",
        "petite",
        "phobic",
        "plucky",
        "polite",
        "poor",
        "pretty",
        "pricey",
        "prickly",
        "private",
        "profuse",
        "proud",
        "public",
        "puffy",
        "pumped",
        "puny",
        "pushy",
        "quaint",
        "quick",
        "quiet",
        "quirky",
        "ragged",
        "rainy",
        "rampant",
        "rapid",
        "rare",
        "raspy",
        "ratty",
        "ready",
        "real",
        "rebel",
        "rich",
        "right",
        "rigid",
        "ritzy",
        "robust",
        "roomy",
        "rotten",
        "rough",
        "round",
        "royal",
        "ruddy",
        "rude",
        "rural",
        "rustic",
        "sable",
        "sad",
        "safe",
        "salty",
        "same",
        "sassy",
        "savory",
        "scarce",
        "scared",
        "scary",
        "scrawny",
        "second",
        "secret",
        "sedate",
        "seemly",
        "selfish",
        "serious",
        "shaggy",
        "shaky",
        "sharp",
        "shiny",
        "short",
        "shrill",
        "shut",
        "shy",
        "sick",
        "silent",
        "silky",
        "silly",
        "simple",
        "sincere",
        "skinny",
        "sleepy",
        "slim",
        "slimy",
        "sloppy",
        "slow",
        "small",
        "smart",
        "smelly",
        "smiling",
        "smoggy",
        "smooth",
        "sneaky",
        "snotty",
        "soft",
        "soggy",
        "solid",
        "somber",
        "sore",
        "special",
        "spiffy",
        "spiky",
        "spooky",
        "spotted",
        "spotty",
        "stale",
        "steady",
        "steep",
        "stingy",
        "stormy",
        "strange",
        "striped",
        "strong",
        "sturdy",
        "subdued",
        "sudden",
        "sulky",
        "super",
        "superb",
        "supreme",
        "swanky",
        "sweet",
        "swift",
        "tacit",
        "tacky",
        "tall",
        "tame",
        "tawdry",
        "tedious",
        "teeny",
        "telling",
        "tender",
        "tense",
        "tenuous",
        "thick",
        "thin",
        "third",
        "thirsty",
        "tidy",
        "tight",
        "tiny",
        "tired",
        "torpid",
        "tough",
        "trashy",
        "tricky",
        "trite",
        "true",
        "typical",
        "unique",
        "unkempt",
        "unknown",
        "unruly",
        "untidy",
        "unused",
        "unusual",
        "upbeat",
        "uppity",
        "upset",
        "uptight",
        "used",
        "useful",
        "utopian",
        "vacuous",
        "vague",
        "various",
        "vast",
        "verdant",
        "versed",
        "vulgar",
        "wacky",
        "waggish",
        "waiting",
        "wakeful",
        "wanting",
        "warm",
        "wary",
        "watery",
        "weak",
        "wealthy",
        "weary",
        "wicked",
        "wiggly",
        "wild",
        "willing",
        "windy",
        "wiry",
        "wise",
        "wistful",
        "witty",
        "wooden",
        "woozy",
        "wrong",
        "wry",
        "young",
        "zany",
        "zealous",
        "zippy",
    ];

    private $animals = [
        "Alpaca",
        "Ant",
        "Ape",
        "Donkey",
        "Baboon",
        "Badger",
        "Bat",
        "Bear",
        "Beaver",
        "Bee",
        "Bison",
        "Boar",
        "Buffalo",
        "Camel",
        "Capybara",
        "Caribou",
        "Cat",
        "Cattle",
        "Chamois",
        "Cheetah",
        "Chicken",
        "Chough",
        "Clam",
        "Cobra",
        "Cod",
        "Coyote",
        "Crab",
        "Crane",
        "Crow",
        "Curlew",
        "Deer",
        "Dog",
        "Dogfish",
        "Dolphin",
        "Dove",
        "Duck",
        "Dugong",
        "Dunlin",
        "Eagle",
        "Echidna",
        "Eel",
        "Eland",
        "Elk",
        "Emu",
        "Falcon",
        "Ferret",
        "Finch",
        "Fish",
        "Fly",
        "Fox",
        "Frog",
        "Gaur",
        "Gazelle",
        "Gerbil",
        "Giraffe",
        "Gnat",
        "Gnu",
        "Goat",
        "Goldfish",
        "Goose",
        "Gorilla",
        "Goshawk",
        "Grouse",
        "Guanaco",
        "Gull",
        "Hamster",
        "Hare",
        "Hawk",
        "Hedgehog",
        "Heron",
        "Herring",
        "Hornet",
        "Horse",
        "Hyena",
        "Ibex",
        "Ibis",
        "Jackal",
        "Jaguar",
        "Jay",
        "Kangaroo",
        "Koala",
        "Kouprey",
        "Kudu",
        "Lapwing",
        "Lark",
        "Lemur",
        "Leopard",
        "Lion",
        "Llama",
        "Lobster",
        "Locust",
        "Loris",
        "Louse",
        "Magpie",
        "Mallard",
        "Manatee",
        "Mandrill",
        "Mantis",
        "Marten",
        "Meerkat",
        "Mink",
        "Mole",
        "Mongoose",
        "Monkey",
        "Moose",
        "Mosquito",
        "Mouse",
        "Mule",
        "Narwhal",
        "Newt",
        "Octopus",
        "Okapi",
        "Opossum",
        "Oryx",
        "Ostrich",
        "Otter",
        "Owl",
        "Oyster",
        "Panther",
        "Parrot",
        "Peafowl",
        "Pelican",
        "Penguin",
        "Pheasant",
        "Pig",
        "Pigeon",
        "Pony",
        "Porpoise",
        "Quail",
        "Quelea",
        "Quetzal",
        "Rabbit",
        "Raccoon",
        "Rail",
        "Ram",
        "Rat",
        "Raven",
        "Rook",
        "Salmon",
        "Sardine",
        "Scorpion",
        "Seahorse",
        "Seal",
        "Shark",
        "Sheep",
        "Shrew",
        "Skunk",
        "Snail",
        "Snake",
        "Sparrow",
        "Spider",
        "Squid",
        "Stork",
        "Swallow",
        "Swan",
        "Tapir",
        "Tarsier",
        "Termite",
        "Tiger",
        "Toad",
        "Trout",
        "Turkey",
        "Turtle",
        "Viper",
        "Vulture",
        "Wallaby",
        "Walrus",
        "Wasp",
        "Weasel",
        "Whale",
        "Wildcat",
        "Wolf",
        "Wombat",
        "Worm",
        "Wren",
        "Yak",
        "Zebra",
    ];

    public function getAdjectives()
    {
        return $this->adjectives;
    }

    public function getAnimals()
    {
        return $this->animals;
    }
}
