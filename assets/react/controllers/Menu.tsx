import { Button } from "@/components/ui/button";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from "@/components/ui/tooltip";
import { Home, Mail, Scale, User } from "lucide-react";
import * as React from "react";

export default function Menu({ path }) {
  return (
    <nav className="flex flex-col items-center gap-4 px-2 sm:py-5 sm:w-20">
      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger asChild>
            <a
              href={path.homePath}
              className="flex items-center justify-center transition-colors rounded-lg h-9 w-9 text-muted-foreground hover:text-foreground md:h-8 md:w-8"
            >
              <Home className="w-5 h-5" />
              <span className="sr-only">Home</span>
            </a>
          </TooltipTrigger>
          <TooltipContent side="right">Home</TooltipContent>
        </Tooltip>
      </TooltipProvider>

      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger asChild>
            <a
              href={path.contactPath}
              className="flex items-center justify-center transition-colors rounded-lg h-9 w-9 text-muted-foreground hover:text-foreground md:h-8 md:w-8"
            >
              <Mail className="w-5 h-5" />
              <span className="sr-only">Contact Us</span>
            </a>
          </TooltipTrigger>
          <TooltipContent side="right">Contact Us</TooltipContent>
        </Tooltip>
      </TooltipProvider>

      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger asChild>
            <a
              href={path.legalPath}
              className="flex items-center justify-center transition-colors rounded-lg h-9 w-9 text-muted-foreground hover:text-foreground md:h-8 md:w-8"
            >
              <Scale className="w-5 h-5" />
              <span className="sr-only">Legal</span>
            </a>
          </TooltipTrigger>
          <TooltipContent side="right">Legal</TooltipContent>
        </Tooltip>
      </TooltipProvider>

      <TooltipProvider>
        <Tooltip>
          <TooltipTrigger asChild>
            <a
              href="#"
              className="flex items-center justify-center transition-colors rounded-lg h-9 w-9 text-muted-foreground hover:text-foreground md:h-8 md:w-8"
            >
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="default" size="icon">
                    <User />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                  <DropdownMenuLabel>My Account</DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem>Settings</DropdownMenuItem>
                  <DropdownMenuItem>Support</DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem>Logout</DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
              <span className="sr-only">My Account</span>
            </a>
          </TooltipTrigger>
          <TooltipContent side="right">My Account</TooltipContent>
        </Tooltip>
      </TooltipProvider>
    </nav>
  );
}
