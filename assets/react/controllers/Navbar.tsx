import * as React from "react";
import Logo from "@/components/svg/logo/logo";
import ThemeToggle from "@/components/theme-toggle";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Menu, Slash } from "lucide-react";
import {
  Sheet,
  SheetContent,
  SheetHeader,
  SheetTitle,
  SheetTrigger,
  SheetDescription,
} from "@/components/ui/sheet";

export default function Navbar({ path }) {
  return (
    <div className="flex items-center h-16 max-w-screen-xl mx-auto sm:h-24">

      <div className="items-center hidden mr-4 md:flex">

        <a className="flex items-center mr-6 space-x-2" href="/">
          <Logo className="w-12 h-12" />
          <span className="hidden font-bold sm:inline-block text-text dark:text-darkText">Globnot</span>
        </a>

        <div className="ml-10">
          <Breadcrumb>
            <BreadcrumbList>
              <BreadcrumbItem>
                <BreadcrumbLink href={path.homePath}>Home</BreadcrumbLink>
              </BreadcrumbItem>
              <BreadcrumbSeparator>
                <Slash />
              </BreadcrumbSeparator>
              <BreadcrumbItem>
                <BreadcrumbLink href={path.homePath}>Other</BreadcrumbLink>
              </BreadcrumbItem>
            </BreadcrumbList>
          </Breadcrumb>
        </div>

      </div>

      {/* Utilisation de Sheet pour le menu mobile */}

      <Sheet>
        <SheetTrigger asChild>
          <button
            className="inline-flex items-center justify-center px-0 py-2 mr-2 text-base font-medium transition-colors rounded-md whitespace-nowrap focus-visible:outline-none focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:text-accent-foreground h-9 hover:bg-transparent focus-visible:bg-transparent focus-visible:ring-0 focus-visible:ring-offset-0 md:hidden"
            type="button"
            aria-haspopup="dialog"
          >
            <Menu />
            <span className="sr-only">Toggle Menu</span>
          </button>
        </SheetTrigger>

        <SheetContent>
          <SheetHeader>
            <SheetTitle>
              <a className="flex items-center mr-6 space-x-2" href="/">
                <Logo className="w-10 h-10" />
                <span className="px-2">Globnot</span>
              </a>
            </SheetTitle>
          </SheetHeader>
          <nav className="flex flex-col items-start space-y-4 font-semibold">
            <a href={path.homePath}>
              Home
            </a>
            <a href={path.homePath}>
              Contact
            </a>
            <a href={path.homePath}>
              Legal
            </a>
          </nav>
        </SheetContent>
      </Sheet>

      <div className="flex items-center justify-end flex-1 space-x-2">
        <nav className="flex items-center">
          <ThemeToggle />
        </nav>
      </div>
    </div>
  );
}
