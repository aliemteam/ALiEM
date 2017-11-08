import * as React from 'react';
import render from '../../utils/render';
import { LogoIcon } from '../../utils/svg';
import { LogoText } from './graphics';
import SearchBox from './search';
import SecondaryHeader from './secondary';

interface MenuItem {
    ID: number;
    children?: MenuItem[];
    menu_order: number;
    title: string;
    url: string;
}

declare const __header: MenuItem[];

interface Props {
    menuItems: MenuItem[];
}

interface State {
    navOpen: boolean;
    searchIsActive: boolean;
}

class Header extends React.Component<Props, State> {
    constructor(props: Props) {
        super(props);
        this.state = {
            navOpen: false,
            searchIsActive: false,
        };
    }

    toggleNav = () => this.setState(prev => ({ navOpen: !prev.navOpen }));

    handleSearchboxEvent = (kind: 'activate' | 'deactivate') => {
        this.setState(prev => ({ ...prev, searchIsActive: kind === 'activate' }));
    };

    render() {
        return (
            <div>
                <SecondaryHeader />
                <div className="header-primary">
                    <div className="header-primary__container">
                        <div className="header-primary__left">
                            <h2>
                                <a
                                    href="/"
                                    className="header-primary__logo"
                                    aria-label="Navigate to home page"
                                >
                                    <LogoIcon />
                                    {!this.state.navOpen && (
                                        <LogoText
                                            className={
                                                this.state.searchIsActive
                                                    ? 'logo-text--search-active'
                                                    : ''
                                            }
                                        />
                                    )}
                                </a>
                            </h2>
                        </div>
                        <nav className="header-primary__right">
                            <SearchBox
                                className="header__search--desktop"
                                instance={0}
                                userEventHandler={this.handleSearchboxEvent}
                            />
                            <button
                                className={`hamburger hamburger--squeeze ${
                                    this.state.navOpen ? 'is-active' : ''
                                }`}
                                type="button"
                                aria-label="Menu"
                                aria-controls="nav-menu"
                                onClick={this.toggleNav}
                                aria-expanded={this.state.navOpen}
                            >
                                <span className="hamburger-box">
                                    <span className="hamburger-inner" />
                                </span>
                            </button>
                        </nav>
                    </div>
                </div>
                <nav
                    id="nav-menu"
                    className={`nav-menu ${this.state.navOpen ? '' : 'nav-menu--closed'}`}
                >
                    <div className="nav-menu__container">
                        <ul role="menu">
                            <Navigation items={this.props.menuItems} level={1} />
                        </ul>
                    </div>
                </nav>
            </div>
        );
    }
}

interface NavProps {
    items: MenuItem[];
    level: number;
}

const Navigation = ({ items, level }: NavProps): any =>
    items.map((item, i) => (
        <li key={item.ID} role="none">
            {item.url === '#' ? (
                <span>{item.title}</span>
            ) : (
                <a role="menuitem" href={item.url} tabIndex={i === 0 && level === 0 ? 0 : -1}>
                    {item.title}
                </a>
            )}

            {item.children !== undefined && (
                <ul role="menu" tabIndex={-1} aria-label={item.title} aria-expanded={false}>
                    <Navigation items={item.children} level={level + 1} />
                </ul>
            )}
        </li>
    ));

render(<Header menuItems={__header} />, '#header__main');
