import { clsx } from "clsx";
import { twMerge } from "tailwind-merge";
import Config from "../../tailwind.config";
/**
 *
 * @returns {string}
 */
export function cn(...inputs) {
    // inputs = inputs.filter(Boolean).map((input) => {
    //     if (typeof input === "string" && input.includes(" ")) {
    //         return input.split(" ").map((input) => Config.prefix + input);
    //     } else if (Array.isArray(input) && input.length) {
    //         return input.map((input) => Config.prefix + input);
    //     } else {
    //         return Config.prefix + input;
    //     }
    // });
    console.log(inputs);
    return twMerge(clsx(inputs))
        .split(" ")
        .map((input) => Config.prefix + input)
        .join(" ");
}
