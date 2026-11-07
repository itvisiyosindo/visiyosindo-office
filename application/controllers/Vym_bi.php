<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Vym_bi — Controller for VYM Business Intelligence (Bloomberg Terminal Edition)
 * Navigation: Accounting -> Business Intelligence
 *
 * @property CI_Input $input
 * @property CI_Output $output
 * @property CI_Loader $load
 * @property CI_DB_query_builder $db
 * @property Md_vym_bi $md_vym_bi
 */
class Vym_bi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('md_vym_bi');
        $this->load->helper(['url', 'form']);

        // Respect existing user roles & permissions (Accounting, Admin, HRD, Executive)
        $has_access = (
            (function_exists('isAccountingUser') && isAccountingUser()) ||
            (function_exists('isAdmin') && isAdmin()) ||
            (function_exists('isHrd') && isHrd()) ||
            (function_exists('isEksekutif') && isEksekutif())
        );

        if (!$has_access) {
            redirect(base_url('dashboard'));
        }
    }

    private function id_navbar()
    {
        return 'accounting';
    }

    /**
     * Metadata for all 8 VYM Business Intelligence submodules
     */
    private function getSubmodulesRegistry()
    {
        return [
            'executive_overview' => [
                'no'       => '01',
                'slug'     => 'executive_overview',
                'code'     => 'VYM:EXEC_OVW',
                'title'    => 'Executive Overview',
                'subtitle' => 'Institutional Command Center • Order Book, Billed Receivables, Tax Basis, Payable Obligations & Verified Cash Outflow',
                'icon'     => 'fas fa-terminal',
                'phase'    => 'PHASE 2 • ACTIVE'
            ],
            'financial_performance' => [
                'no'       => '02',
                'slug'     => 'financial_performance',
                'code'     => 'VYM:FIN_PERF',
                'title'    => 'Financial Performance',
                'subtitle' => 'Comparative Period Financial Statements, DPP/PPN Tax Reconciliation & Operating Ratios',
                'icon'     => 'fas fa-balance-scale',
                'phase'    => 'PHASE 3 • ACTIVE'
            ],
            'revenue_analytics' => [
                'no'       => '03',
                'slug'     => 'revenue_analytics',
                'code'     => 'VYM:REV_ANLY',
                'title'    => 'Revenue Analytics',
                'subtitle' => 'Customer Order-to-Invoice Conversion Funnel, Unbilled Backlog & Billing Velocity',
                'icon'     => 'fas fa-chart-line',
                'phase'    => 'PHASE 3 • ACTIVE'
            ],
            'profitability_analysis' => [
                'no'       => '04',
                'slug'     => 'profitability_analysis',
                'code'     => 'VYM:PROF_GUARD',
                'title'    => 'Profitability Analysis',
                'subtitle' => 'Operating Contribution Margin, Supplier Price Catalog Audit & COGS Reliability Guardrails',
                'icon'     => 'fas fa-percentage',
                'phase'    => 'PHASE 3 • ACTIVE'
            ],
            'cashflow_intelligence' => [
                'no'       => '05',
                'slug'     => 'cashflow_intelligence',
                'code'     => 'VYM:CASH_INTL',
                'title'    => 'Cashflow Intelligence',
                'subtitle' => 'Verified Down Payments, Settled Freight & Payroll Outflows vs Unpaid Vendor Obligations',
                'icon'     => 'fas fa-exchange-alt',
                'phase'    => 'PHASE 3 • ACTIVE'
            ],
            'business_performance' => [
                'no'       => '06',
                'slug'     => 'business_performance',
                'code'     => 'VYM:BIZ_PERF',
                'title'    => 'Business Performance',
                'subtitle' => 'Customer Concentration (HHI), SKU Commercial Velocity, Principal Supplier Catalog & Operational SLA',
                'icon'     => 'fas fa-briefcase',
                'phase'    => 'PHASE 4 • ACTIVE'
            ],
            'risk_forecasting' => [
                'no'       => '07',
                'slug'     => 'risk_forecasting',
                'code'     => 'VYM:RISK_FCST',
                'title'    => 'Risk & Forecasting',
                'subtitle' => 'Quantitative 3-Month Forward Projection (FORECAST), Statistical Anomaly Z-Score & Enterprise Risk Radar',
                'icon'     => 'fas fa-shield-alt',
                'phase'    => 'PHASE 5 • ACTIVE'
            ],
            'ai_business_analyst' => [
                'no'       => '08',
                'slug'     => 'ai_business_analyst',
                'code'     => 'VYM:AI_ANALYST',
                'title'    => 'AI Business Analyst',
                'subtitle' => 'Automated Institutional Financial Commentary, Strategic Diagnostics & Interactive Natural-Language Terminal',
                'icon'     => 'fas fa-robot',
                'phase'    => 'PHASE 5 • ACTIVE'
            ]
        ];
    }

    /**
     * Render a VYM BI Submodule View inside the existing Office System Master Layout
     */
    private function renderSubmodule($sub_slug)
    {
        $registry = $this->getSubmodulesRegistry();
        if (!isset($registry[$sub_slug])) {
            $sub_slug = 'executive_overview';
        }

        $preset       = $this->input->get('preset') ?: 'YTD';
        $start_custom = $this->input->get('start_date');
        $end_custom   = $this->input->get('end_date');
        $interval     = $this->input->get('interval') ?: '1M';

        $resolved_filter = $this->md_vym_bi->resolveDateFilter($preset, $start_custom, $end_custom);
        $overview_data   = $this->md_vym_bi->getExecutiveOverviewData(
            $resolved_filter['start_date'],
            $resolved_filter['end_date'],
            $interval
        );

        if (!empty($overview_data['used_fallback_all'])) {
            $resolved_filter['preset']     = 'ALL';
            $resolved_filter['start_date'] = $overview_data['filter']['start_date'];
            $resolved_filter['end_date']   = $overview_data['filter']['end_date'];
            $resolved_filter['label']      = 'All-Time Historical Records (Auto-Loaded)';
        }

        $active_meta = $registry[$sub_slug];

        $page_data = [
            'switch'          => $this->id_navbar(),
            'page_name'       => 'vym_bi/v_executive_overview',
            'page_title'      => 'VYM BI — ' . $active_meta['title'],
            'bi_active_sub'   => $sub_slug,
            'bi_active_meta'  => $active_meta,
            'bi_registry'     => $registry,
            'bi_filter'       => $resolved_filter,
            'bi_interval'     => $interval,
            'bi_initial_data' => $overview_data
        ];

        $this->load->view('index', $page_data);
    }

    // =========================================================================
    // SUBMODULE ENTRYPOINTS (1 to 8)
    // =========================================================================

    public function index()
    {
        $this->renderSubmodule('executive_overview');
    }

    public function executive_overview()
    {
        $this->renderSubmodule('executive_overview');
    }

    public function financial_performance()
    {
        $this->renderSubmodule('financial_performance');
    }

    public function revenue_analytics()
    {
        $this->renderSubmodule('revenue_analytics');
    }

    public function profitability_analysis()
    {
        $this->renderSubmodule('profitability_analysis');
    }

    public function cashflow_intelligence()
    {
        $this->renderSubmodule('cashflow_intelligence');
    }

    public function business_performance()
    {
        $this->renderSubmodule('business_performance');
    }

    public function risk_forecasting()
    {
        $this->renderSubmodule('risk_forecasting');
    }

    public function ai_business_analyst()
    {
        $this->renderSubmodule('ai_business_analyst');
    }

    // =========================================================================
    // JSON API ENDPOINT FOR INTERACTIVE TERMINAL FILTERING
    // =========================================================================

    public function api_overview_data()
    {
        $this->output->set_content_type('application/json');

        $preset       = $this->input->get('preset') ?: 'YTD';
        $start_custom = $this->input->get('start_date');
        $end_custom   = $this->input->get('end_date');
        $interval     = $this->input->get('interval') ?: '1M';

        $resolved_filter = $this->md_vym_bi->resolveDateFilter($preset, $start_custom, $end_custom);
        $data = $this->md_vym_bi->getExecutiveOverviewData(
            $resolved_filter['start_date'],
            $resolved_filter['end_date'],
            $interval
        );

        $data['resolved_filter'] = $resolved_filter;
        echo json_encode($data);
    }
}
