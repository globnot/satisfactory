import { Moon, Sun } from "lucide-react";
import { useEffect } from "react";
import { Button } from "./ui/button";
import React = require("react");

const ThemeToggle = () => {
  useEffect(() => {
    const body = document.body;
    const toggleButton = document.getElementById("theme-toggle");

    const handleToggle = () => {
      body.classList.toggle("dark");
      localStorage.setItem(
        "theme",
        body.classList.contains("dark") ? "dark" : "light"
      );
    };

    toggleButton?.addEventListener("click", handleToggle);

    if (localStorage.getItem("theme") === "dark") {
      body.classList.add("dark");
    } else {
      body.classList.remove("dark");
    }

    return () => {
      toggleButton?.removeEventListener("click", handleToggle);
    };
  }, []);

  return (
    <Button id="theme-toggle" className="w-10 h-10">
      <Sun className="absolute w-6 h-6 transition-all scale-100 rotate-0 dark:-rotate-90 dark:scale-0" />
      <Moon className="absolute w-6 h-6 transition-all scale-0 rotate-90 dark:rotate-0 dark:scale-100" />
      <span className="sr-only">Toggle theme</span>
    </Button>
  );
};

export default ThemeToggle;
