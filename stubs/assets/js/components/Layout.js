import React from "react";
import { Link, usePage } from "@inertiajs/react";
import Routing from "fos-router";

const Layout = ({ children }) => {
  const { component, props } = usePage();

  return (
    <>
      <header>
        <nav>
          <ul>
            <li>
              <Link
                href={Routing.generate("app_home")}
                className={component === "Home" ? "active" : ""}
                aria-current={component === "Home"}
              >
                Home
              </Link>
            </li>
            <li>
              <Link
                href={Routing.generate("app_about")}
                className={component === "About" ? "active" : ""}
                aria-current={component === "About"}
              >
                About Us
              </Link>
            </li>
            <li>
              <Link
                href={Routing.generate("app_contact")}
                className={component === "Contact" ? "active" : ""}
                aria-current={component === "Contact"}
              >
                Contact Us
              </Link>
            </li>
          </ul>
        </nav>
      </header>

      <main role="main">
        {props.flash.success && (
          <div className="alert alert-success" role="alert">
            {props.flash.success}
          </div>
        )}

        {children}
      </main>

      <footer>
        <p>
          Built with &hearts; by the folks at{" "}
          <a href="https://parlonscode.com">Parlons Code</a>.
        </p>
      </footer>
    </>
  );
};

export default (page) => <Layout>{page}</Layout>;
