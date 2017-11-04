import * as React from 'react';

interface Props extends React.HTMLProps<HTMLDivElement> {
    instance: number;
    onUserEvent(kind: 'activate' | 'deactivate'): void;
}

interface State {
    isOpen: boolean;
    value: string;
}

export default class SearchBox extends React.Component<Props, State> {
    static autocompleteId = 'algolia-autocomplete-listbox';
    input: HTMLInputElement;

    constructor(props: Props) {
        super(props);
        this.state = {
            isOpen: false,
            value: '',
        };
    }

    componentDidUpdate(_: {}, prevState: State) {
        if (prevState.isOpen && !this.state.isOpen) {
            this.input.dispatchEvent(new Event('input'));
            this.props.onUserEvent('deactivate');
        }
        if (!prevState.isOpen && this.state.isOpen) {
            this.input.focus();
            this.props.onUserEvent('activate');
        }
    }

    bindRefs = (input: HTMLInputElement) => {
        this.input = input;
    };

    handleChange = (e: React.FormEvent<HTMLInputElement>) => {
        const target = e.currentTarget;
        this.setState(prev => ({ ...prev, value: target.value }));
    };

    toggleSearch = () => {
        this.setState(prev => ({ ...prev, value: '', isOpen: !prev.isOpen }));
    };

    render() {
        const { instance, onUserEvent, ...divProps } = this.props;
        return (
            <div
                className="header__search"
                {...{ ...divProps, className: `header__search ${divProps.className || ''}` }}
            >
                <form
                    role="search"
                    className={`searchform ${this.state.isOpen ? 'searchform__open' : ''}`}
                    method="get"
                    action="/"
                >
                    <div className="search-table">
                        <div className="search-field">
                            <input
                                ref={this.bindRefs}
                                type="text"
                                value={this.state.value}
                                name="s"
                                onBlur={this.toggleSearch}
                                onChange={this.handleChange}
                                required={true}
                                className="s aa-input"
                                placeholder="Search ..."
                                aria-required={true}
                                aria-label="Search ..."
                                role="combobox"
                                aria-autocomplete="list"
                                aria-expanded={
                                    this.state.value && this.state.value.trim().length > 0
                                        ? true
                                        : false
                                }
                                aria-owns={`${SearchBox.autocompleteId}-${instance}`}
                                aria-controls={`${SearchBox.autocompleteId}-${instance}`}
                            />
                            <pre
                                aria-hidden="true"
                                style={{
                                    position: 'absolute',
                                    visibility: 'hidden',
                                    whiteSpace: 'pre',
                                    fontFamily: 'Arial',
                                    fontSize: 12,
                                }}
                            />
                        </div>
                    </div>
                </form>
                <button
                    aria-label="Click to toggle search field"
                    onClick={this.toggleSearch}
                    className={`header__search__button ${this.state.isOpen
                        ? 'header__search__button-hidden'
                        : ''}`}
                >
                    <span
                        className={`dashicons dashicons-${this.state.isOpen ? 'no' : 'search'}`}
                    />
                </button>
            </div>
        );
    }
}
